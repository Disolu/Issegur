<?php

class FacturaController extends BaseController{


  public function generar(){
    if (Auth::check())
    {
        $serie = Serie::find(1);
        $serie->serie = str_pad($serie->serie,3,'0',STR_PAD_LEFT);
        $serie->number = str_pad($serie->number,5,'0',STR_PAD_LEFT);
        return View::make('intranet.facturas.generar',compact('serie'));
    }
    else{
        return View::make('intranet.auth.login');
    }
  }

  public function cancelar($id){
    if (Auth::check())
    {
        $factura = Factura::find($id);
        if($factura){
          $factura->estado = 0;
          $factura->save();
        }
        return Redirect::to('intranet/facturas');
    }
    else{
        return View::make('intranet.auth.login');
    }


  }

  public function configurar(){
    if (Auth::check())
    {
        $serie = Serie::find(1);
        return View::make('intranet.facturas.configurar',compact('serie'));
    }
    else{
        return View::make('intranet.auth.login');
    }
  }

  public function updateConfig(){
    $serie = Serie::find(1);
    $serie->serie = Input::get('serie');
    $serie->number  = Input::get('number');
    $serie->save();
    return Response::json(array('complete'=>TRUE), 200)
        ->setCallback(Input::get('callback'));
  }


  public function consultar(){
    if (Auth::check())
    {
        return View::make('intranet.facturas.consultar');
    }
    else{
        return View::make('intranet.auth.login');
    }
  }


  public function reporte(){
    if (Auth::check())
    {
        return View::make('intranet.facturas.reporte');
    }
    else{
        return View::make('intranet.auth.login');
    }
  }



  public function report(){
    $inidate = Input::get('inidate');
    $enddate = Input::get('enddate');

    $report = new stdClass();
    $report->total = 0;
    $report->anuladas = 0;
    $report->valor = 0;

    $facturas = Factura::where('userid','<>',0);
    if($inidate){
      $facturas = $facturas->where('date','>=',$inidate);
    }

    if($enddate){
      $facturas = $facturas->where('date','<=',$enddate);
    }

    $facturas = $facturas->get();

    foreach ($facturas as $f) {
      if($f->estado == 1){
        $report->total++;
        $f->data = unserialize($f->data);
        $report->valor += $f->data['total'];
      }else{
        $report->anuladas++;
      }
    }


    return Response::json(array('report'=>$report), 200)
        ->setCallback(Input::get('callback'));

  }


  public function excel(){

    $inidate = Input::get('inidate');
    $enddate = Input::get('enddate');

    $report = new stdClass();
    $report->total = 0;
    $report->anuladas = 0;
    $report->valor = 0;

    $facturas = Factura::where('userid','<>',0);
    if($inidate!='undefined'){
      $facturas = $facturas->where('date','>=',$inidate);
    }

    if($inidate!='undefined'){
      $facturas = $facturas->where('date','<=',$enddate);
    }

    $facturas = $facturas->get();

    return Excel::create('Reporte',
        function($excel) use($facturas){
          $excel->sheet('Reporte', function($sheet) use($facturas){
              $sheet->loadView('excel.reporteFacturas')->with('facturas', $facturas);
            });
        })->export('xlsx');
  }



  public function loadbyruc(){
    $empresa = array();
    if(Input::has('ruc')){
      $ruc = Input::get('ruc');
      $empresa = Empresa::where('emp_ruc',$ruc)->first();
    }

    return Response::json(array('empresa'=>$empresa), 200)
        ->setCallback(Input::get('callback'));
  }

  public function createedit(){
    $empresa = FALSE;
    if(Input::has('id')){
      $id = Input::get('id');
      $empresa = Empresa::find($id);
    }else{
      if(Input::has('ruc')){
        $ruc = Input::get('id');
        $empresa = Empresa::where('emp_ruc',$ruc)->first();
      }

      if(!$empresa){
        $empresa = new Empresa;
      }

    }


    if($empresa){
      $empresa->emp_ruc = Input::get('ruc');
      $empresa->emp_razon_social = Input::get('name');
      $empresa->emp_direccion = Input::get('address');
      $empresa->save();
    }


    return Response::json(array('empresa'=>$empresa), 200)
        ->setCallback(Input::get('callback'));
  }


  public function show($id){
    if (Auth::check())
    {
        $factura = Factura::find($id);
        $factura->data = unserialize($factura->data);
        $factura->date = date('d F Y',strtotime($factura->date));
        return View::make('intranet.facturas.ver',compact('factura'));
    }
    else{
        return View::make('intranet.auth.login');
    }
  }

  public function search(){

    $facturas = Factura::with('user')->where('userid','<>',0);

    if(Input::has('ruc')){
      $facturas->where('ruc','LIKE','%'.Input::get('ruc').'%');
    }

    if(Input::has('name')){
      $facturas->where('empresa','LIKE','%'.Input::get('name').'%');
    }


    $facturas = $facturas->get();

    foreach($facturas as &$f){
      $f->data = unserialize($f->data);
    }

    return Response::json(array('facturas'=>$facturas), 200)
        ->setCallback(Input::get('callback'));
  }


  public function store(){
    $data = Input::all();
    $serie = Serie::find(1);
    $factura = new Factura();
    $factura->ruc = $data['ruc'];
    $factura->empresa = $data['empresa'];
    $factura->direccion = $data['address'];
    $factura->estado = 1;
    $factura->date = date('Y-m-d');
    $factura->letras = $data['letters'];
    $factura->userid = Auth::user()->id;
    $factura->number = str_pad($serie->serie,3,'0',STR_PAD_LEFT).'-'.str_pad($serie->number,5,'0',STR_PAD_LEFT);

    unset($data['ruc']);
    unset($data['empresa']);
    unset($data['letters']);
    unset($data['address']);
    unset($data['id']);

    $factura->data = serialize($data);

    $factura->save();


    //Update Serie number
    $serie->number++;
    $serie->save();


    return Response::json(array('factura'=>$factura), 200)
        ->setCallback(Input::get('callback'));

  }



}














