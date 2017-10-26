<?php

namespace App;

use DB;
use App\Postvote;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Post extends Model
{
    protected $table = 'post';
    protected $fillable = ['user_id','title','url','description','total_up_votes','total_down_votes','score'];
     
    /**
     * used to join with Modules\Admin\Models\User
     * 
     * @return type
     */
    public function profile()
    {
        return $this->hasOne('App\Users', 'id', 'user_id');
    }
    
    /**
     * used to join with Modules\Admin\Models\PostVote
     * 
     * @return type
     */
    public function userPost()
    {
        return $this->hasMany('App\Postvote', 'post_id', 'id');
    }
    
    public static function data($request) {
       $alias = "p"; // Table alias

       $tableName = 'post';//Table Name

       $sIndexColumn = $alias.".id";// index column

       $columns  =  array('url','total_up_votes','total_down_votes','score','id','description'); // Table columns         

       $sTable  = $tableName.' '.$alias;
       
       $aColumns = array();

       foreach($columns as $value) $aColumns[] = $alias .'.'. $value;

       /*
        * Paging
        */
       $sLimit = "";
       if ( isset( $request->iDisplayStart) && $request->iDisplayLength != '-1' )
       {          

          $sLimit = "LIMIT ".intval( $request->iDisplayStart).", ".
           intval( $request->iDisplayLength);

       }    

       /*
        * Ordering
        */
       $sOrder = "";
       if ( isset( $request->iSortCol_0 ) )
       {
           $sOrder = "ORDER BY  ";
           for ( $i=0 ; $i<intval( $request->iSortingCols ) ; $i++ )
           { 
               if ( $request->{'bSortable_'.intval($request->{'iSortCol_'.$i}) } == "true" )
               {
                   $sOrder .= $aColumns[ intval( $request->{'iSortCol_'.$i}  ) ]."
               ".($request->{'sSortDir_'.$i}==='asc' ? 'asc' : 'desc') .", ";
               }
           }

           $sOrder = substr_replace( $sOrder, "", -2 );

           if ( $sOrder == "ORDER BY" )
           {
               $sOrder = "";
           }
       }
       $sOrder = " ORDER BY ".$alias.".score desc";
       $sWhere = "";
       if ( isset($request->sSearch) && $request->sSearch != "" )
       {
           $sWhere = "WHERE (";
           for ( $i=0 ; $i<count($aColumns) ; $i++ )
           {
               $sWhere .= " ".$aColumns[$i]." LIKE '%".trim( $request->sSearch )."%' OR ";
           }
           $sWhere = substr_replace( $sWhere, "", -3 );
               $sWhere .= ") ";
       }

       /* Individual column filtering */
       for ( $i=0 ; $i<count($aColumns) ; $i++ )
       {
               if ( isset($request->{'bSearchable_'.$i}) && $request->{'bSearchable_'.$i} == "true" && $request->{'sSearch_'.$i} != '' )
           {
               if ( $sWhere == "" )
               {
                   $sWhere = "WHERE ";
               }
               else
               {
                   $sWhere .= " AND ";
               }
                       $sWhere .= "".$aColumns[$i]." LIKE '%".trim($request->{'sSearch_'.$i})."%' ";                        
           }
       }

       $sQuery  = "SELECT SQL_CALC_FOUND_ROWS ".str_replace(" , ", " ", implode(", ", $aColumns))." "; 
       $sQuery .= "FROM $sTable ";
       $sQuery .= "$sWhere ";
       $sQuery .= "$sOrder ";
       $sQuery .= "$sLimit";

       $rResult  = DB::select($sQuery);

       $sQuery = "SELECT FOUND_ROWS()";  

       $rResultFilterTotal = DB::select($sQuery);

       $iFilteredTotal = $rResultFilterTotal[0]->{'FOUND_ROWS()'};

       $sQuery = "
       SELECT COUNT(".$sIndexColumn.")
       FROM   $sTable $sWhere
       ";

       $rResultTotal  = DB::select($sQuery);

       $iTotal = $rResultTotal[0]->{'COUNT('.$sIndexColumn.')'};         

       $output = array(
       "sEcho" => intval($request->sEcho),
       "iTotalRecords" => $iTotal,
       "iTotalDisplayRecords" => $iFilteredTotal,        
       "aaData" => array()
      );

     $aCount = isset($request->iDisplayStart) ? $request->iDisplayStart : 0;
     //$aCount = 0;
      foreach($rResult as $aRow)
      {    
           $row=[];
           $row["DT_RowId"] = "row_".$aRow->{'id'};
           $row[] = ++$aCount;  
           $row[] = !empty($aRow->{'url'}) ? $aRow->{'url'} : '-' ;
           $row[] = $aRow->{'total_up_votes'};
           $row[] = $aRow->{'total_down_votes'};
           $row[] = $aRow->{'score'};
           $row[] = $aRow->{'id'};								               
           $row[] = $aRow->{'description'};				               
           $row[] = !empty(Postvote::where(['user_id'=> Auth::user()->id, 'post_id'=>$aRow->{'id'}, 'vote_status'=>1])->get()->toArray()) ? 1 : 0;				               
           $row[] = !empty(Postvote::where(['user_id'=> Auth::user()->id, 'post_id'=>$aRow->{'id'}, 'vote_status'=>0])->get()->toArray()) ? 1 : 0;				               
           $output['aaData'][] = $row;

      }

      return $output;
    }

    /**
     * Update up count and score.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public static function upPostCount($id) {
        $postData = Post::getPostData($id); 
        $updatedUpCount = $postData['total_up_votes'] + 1;
        $updatedScore = $updatedUpCount - $postData['total_down_votes'];
        
        $updatedRows = Post::where('id', $id)->update(['total_up_votes' => $updatedUpCount, 'score'=>$updatedScore]);
        return $updatedRows;
    }
    
    /**
     * Update down count and score.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public static function downPostCount($id) {
        $postData = Post::getPostData($id); 
        $updatedDownCount = $postData['total_down_votes'] + 1;
        $updatedScore = $postData['total_up_votes'] - $updatedDownCount;
        
        $updatedRows = Post::where('id', $id)->update(['total_down_votes' => $updatedDownCount, 'score'=>$updatedScore]);
        return $updatedRows;
    }
    
    /**
     * Get post dat by id.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public static function getPostData($id) {        
        $postData = collect(Post::select('total_up_votes', 'total_down_votes', 'score')->where('id', '=', $id)->get());
        $postData = $postData->toArray();
        return $postData[0];
    }
}
