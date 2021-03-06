<?php

class CultureZoneController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		
        return \View::make('culturezones.index', array(
                    'culturezones' => CultureZones::get()
        ));
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$cultures = DB::table('cultures')->lists('name','id');
		        return \View::make('culturezones.create', array('cultures' => $cultures));
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$validation = Validator::make(\Input::all(), 
        array(
          'name' => 'required'
          ), 
        array(
          'name.required' => "Le nom est obligatoire !"
        )
      );

      if ($validation->fails()) {
          return Redirect::to('culturezone/create')
              ->withErrors($validation)
              ->withInput(\Input::all());
        } else {
          $culturezone = new CultureZones();
          $culturezone->name = \Input::get('name');
          $culturezone->description = \Input::get('description');
          $culturezone->latitude = \Input::get('latitude');
		   $culturezone->longitude = \Input::get('longitude');
          

          $culturezone->save();

		// Save Culture Period Zone 
		$periods  = Input::only('cultureid','datefrom','dateto');

            $cultureid = $periods['cultureid'];
            $datefrom = $periods['datefrom'];
            $dateto = $periods['dateto'];
            

            foreach( $cultureid as $key => $n ) {
				$arrayDateFrom = explode("/", $datefrom[$key]);
				$arrayDateTo = explode("/", $dateto[$key]);
                DB::table('culturezonecultureperiods')->insert(
                    array(
                        'cultureid' => $cultureid[$key],
                        'from' => $arrayDateFrom[2].'-'.$arrayDateFrom[1].'-'.$arrayDateFrom[0] ,
                        'to' => $arrayDateTo[2].'-'.$arrayDateTo[1].'-'.$arrayDateTo[0],
                        'zoneid' => $culturezone->id,
						'created_at'=>date('Y-m-d H:m:s')
                    )
                );
            }


          $modifierUrl = URL::to('culturezone/' . $culturezone->id . '/modify');
          Session::flash('success', "<p>Création effectuée avec succès ! <a href='{$modifierUrl}' class='btn btn-success'>Modifier</a></p>");
          return Redirect::to('culturezones');  
          
        }
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		return \View::make('culturezones.detail')
                    ->with('culturezone', CultureZones::find($id));
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$cultures = DB::table('cultures')->lists('name','id');
		return \View::make('culturezones.edit', array('cultures' => $cultures))
                    ->with('culturezone', CultureZones::find($id));
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update()
	{
		$id= \Input::get('id');
		$validation = Validator::make(\Input::all(), 
        array(
          'name' => 'required'
          ), 
        array(
          'name.required' => "Le nom est obligatoire !"
        )
      );

      if ($validation->fails()) {
          return Redirect::to('culturezone/'.$id.'/modify')
              ->withErrors($validation)
              ->withInput(\Input::all());
        } else {
          $culturezone = CultureZones::find($id);
          
          $culturezone->name = \Input::get('name');
          $culturezone->description = \Input::get('description');
           $culturezone->latitude = \Input::get('latitude');
		   $culturezone->longitude = \Input::get('longitude');
          

          $culturezone->save();
		  
		  
		  // Save Culture Period Zone 
		  // Clear Old Data
		  DB::table('culturezonecultureperiods')->where('zoneid', '=', $culturezone->id)->delete();
		  
		$periods  = Input::only('cultureid','datefrom','dateto');

            $cultureid = $periods['cultureid'];
            $datefrom = $periods['datefrom'];
            $dateto = $periods['dateto'];
            

            foreach( $cultureid as $key => $n ) {
				$arrayDateFrom = explode("/", $datefrom[$key]);
				$arrayDateTo = explode("/", $dateto[$key]);
                DB::table('culturezonecultureperiods')->insert(
                    array(
                        'cultureid' => $cultureid[$key],
                        'from' => $arrayDateFrom[2].'-'.$arrayDateFrom[1].'-'.$arrayDateFrom[0] ,
                        'to' => $arrayDateTo[2].'-'.$arrayDateTo[1].'-'.$arrayDateTo[0],
                        'zoneid' => $culturezone->id,
						'created_at'=>date('Y-m-d H:m:s')
                    )
                );
            }

          $modifierUrl = URL::to('culturezone/' . $culturezone->id . '/modify');
          Session::flash('success', "<p>Modification effectuée avec succès ! <a href='{$modifierUrl}' class='btn btn-success'>Modifier</a></p>");
          return Redirect::to('culturezones');  
          
        }
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}


}
