<html>
<!-- Headings -->
<tr>
  <td><b>Fecha</b></td>
  <td><b>Codigo</b></td>
  <td><b>Com</b></td>
  <td><b>Ruc</b></td>
  <td><b>Empresa</b></td>
  <td><b>Ventas no Grabadas</b></td>
  <td><b>Ventas Grabadas</b></td>
  <td><b>IGV</b></td>
  <td><b>Total</b></td>
  <td><b>Detalle</b></td>
  <td><b>Fecha de Comprobante</b></td>
</tr>

@foreach ($facturas as $f)
  <?php
      $f->number = explode('-',$f->number);
      $f->data = unserialize($f->data);
      $temp = array();
  ?>
    <tr>
      <td>{{$f->date}}</td>
      <td>{{(int)$f->number[0]}}</td>
      <td>{{(int)$f->number[1]}}</td>
      <td>{{$f->ruc}}</td>
      <td>{{$f->empresa}}</td>
      @if($f->data['igv'] == 0)
        <td>{{$f->data['stotal']}}</td>
        <td></td>
        <td></td>
      @else
        <td></td>
        <td>{{$f->data['stotal']}}</td>
        <td>{{$f->data['igv']}}</td>
      @endif
      <td>{{$f->data['total']}}</td>

      <td>
        @if(isset($f->data['items']))
          @foreach($f->data['items'] as $i)
            <?php $temp[] = $i['voucher']?>
          @endforeach
        @endif
        {{implode(' - ',$temp)}}
      </td>
      <td>
        @if(isset($f->data['items']))
          {{$f->data['items'][0]['date']}}
        @endif
      </td>

    </tr>
@endforeach

</html>
