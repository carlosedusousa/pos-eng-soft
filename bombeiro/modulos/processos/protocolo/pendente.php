<?

  $erro="";
  require_once 'lib/loader.php';

  $conn = new BD (BD_HOST, BD_USER, BD_PASS, BD_NOME_ACESSOS);
  if ($conn->get_status()==false) die($conn->get_msg());

  $arquivo = "pendente.php";
  $sql = "SELECT ID_ROTINA, NM_ROTINA FROM ".TBL_ROTINAS." WHERE NM_ARQ_ROTINA ='".$arquivo."'";
  $res = $conn->query($sql);
  $rows_rotina = $conn->num_rows();
  if ($rows_rotina>0) $rotina = $conn->fetch_row();

  $global_obj_sessao->load($rotina["ID_ROTINA"]);
  $usuario = $global_obj_sessao->is_logged_in();

  if ($global_exclusao=="S") {


    if ((@$_POST["hdn_id_solicitacao"]!="") && (@$_POST["hdn_id_cidade"]!="") && (@$_POST["hdn_id_tipo_solicitacao"]!="")){
        

      $query_exclui="DELETE FROM ".TBL_SOLICITACAO." WHERE ".TBL_SOLICITACAO.".ID_CIDADE=".$_POST["hdn_id_cidade"]." AND ".TBL_SOLICITACAO.".ID_SOLICITACAO=".$_POST["hdn_id_solicitacao"]." AND ".TBL_SOLICITACAO.".ID_TIPO_SOLICITACAO='".$_POST["hdn_id_tipo_solicitacao"]."'";
      $conn->query($query_exclui);
      if ($conn->get_status()==false) {
        die($conn->get_msg());
      } else {
        ?>
		<script language="javascript" type="text/javascript">//<!--
		alert("Registro Excluido com Sucesso!");
		//--></script>
        <?
      }
    }
  }

  $arq = explode('/',__FILE__); 
  $arq = end($arq);
  $seta = '<img src="./imagens/seta1.gif" alt="" border="0">&nbsp;';

  switch(@$_GET['ord']) {
  	case 'dt' 	: $ord = 'ORDER BY DT_SOLICITACAO ASC, NM_CIDADE ASC '; 			break;
  	case 'edf'	: $ord = 'ORDER BY NM_EDIFICACOES_LX ASC, DT_SOLICITACAO ASC '; 	break;
  	case 'cid'	: $ord = 'ORDER BY NM_CIDADE ASC, DT_SOLICITACAO ASC '; 			break;
  	default		: $ord = 'ORDER BY DT_SOLICITACAO ASC '; 							break; 
  }

  $sql="SELECT " .TBL_SOLICITACAO.".ID_USUARIO,".TBL_SOLICITACAO.".ID_SOLICITACAO,".TBL_SOLICITACAO.".ID_CIDADE," .TBL_SOLICITACAO.".ID_TIPO_SOLICITACAO, " .TBL_SOLICITACAO.".NM_EDIFICACOES_LX, " ."DATE_FORMAT(DT_SOLICITACAO,'%d/%m/%Y') DT_SOLICITACAOS, " ."(TO_DAYS('".date("Y-m-d")."') - TO_DAYS(DT_SOLICITACAO)) AS DIAS, " .TBL_CIDADE.".NM_CIDADE " ."FROM ".TBL_SOLICITACAO." " ."JOIN ".TBL_CIDADE." USING(ID_CIDADE) WHERE ".TBL_SOLICITACAO.".CH_PROTOCOLADO='S' AND ".TBL_SOLICITACAO.".ID_CIDADE IN (SELECT ID_CIDADE FROM ".TBL_CIDADES_USR." WHERE ID_USUARIO='".$usuario."') $ord";

  $diasd = false;
  $conn->query($sql);
  $rows_pendente=$conn->num_rows();
?>
<script language="javascript" type="text/javascript">//<!--

    function consultaReg(campo,arq) {
      if (campo.value!="") {
        window.open(arq+"?campo="+campo.value,"consulrot","top=5000,left=5000,screenY=5000,screenX=5000,toolbar=no,location=no,directories=no,status=yes,menubar=no,scrollbars=no,resizable=no,width=1,height=1,innerwidth=1,innerheight=1");
      }
    }
    function retorna(frm) {
      frm.btn_incluir.value="Incluir";
      frm.hdn_controle.value="1";
      frm.txt_id_rotina.readOnly=false;
    }
    function envia_pendencia(solicitacao,cidade,tipo) {



      var frm = document.frm_pendencia;
      frm.hdn_id_solicitacao.value=solicitacao; 
      frm.hdn_id_cidade.value=cidade;
      frm.hdn_id_tipo_solicitacao.value=tipo;
      var op = "pendenteChamaProtocolo";
      frm.op_menu.value = op;
      frm.submit();
    }
    function excluir(solicitacao,cidade,tipo) {
      if (window.confirm("Deseja Excluir a Solicita��o?")) {
        var frm = document.frm_pendencia;
       
        frm.hdn_id_solicitacao.value=solicitacao;
        frm.hdn_id_cidade.value=cidade;
        frm.hdn_id_tipo_solicitacao.value=tipo;


        frm.submit();
      }
    }
//--></script>
<body onload="ajustaspan()">



</script>

          <form target="_self" enctype="multipart/form-data" method="post" name="frm_pendencia" > 
        
           <input type="hidden" name="op_menu" value="<?=$_POST['op_menu']?>">

            <input type="hidden" name="hdn_id_solicitacao" value="">
            <input type="hidden" name="hdn_id_cidade" value="">
            <input type="hidden" name="hdn_id_tipo_solicitacao" value="">
            <input type="hidden" name="hdn_controle" value="2">
 
        

            <table width="98%" cellspacing="0" border="0" cellpadding="5" align="center">
              <tr>
                <td>
                <fieldset>
                  <legend>Protocolo Pendente</legend>
                  <table width="100%" cellspacing="1" border="0" cellpadding="5" align="center">
                    <tr style="background-color : #C6E2FF; ">             
                      <th nowrap><? if(@$_GET['ord']=='dt' OR !@$_GET['ord']) echo $seta; ?><a href="<?=$arq?>?ord=dt">Data</a></th>
                      <th nowrap><? if(@$_GET['ord']=='edf') echo $seta; ?><a href="<?=$arq?>?ord=edf">Edifica��o</a></th>
                      <th nowrap><? if(@$_GET['ord']=='cid') echo $seta; ?><a href="<?=$arq?>?ord=cid">Cidade</a></th>
                      <? if ($global_exclusao!="N") { ?>
                      <th width="20" nowrap>Exclus�o</th>
                      <? } ?>
                    </tr>
<?
                    if ($rows_pendente>0) {
                      $cont=1;
                      while ($pendente=$conn->fetch_row()) {
                        $resto=$cont%2;
                        if ($pendente["DIAS"]<30) {
                          $dias="";
                        } else {
                          $dias="color : #ff0101;font-weight : bold;";
                          $diasd=true;
                        }
                        if ($resto!=0) {
?>
                        <tr style="background-color : #4ab; cursor : pointer;<?=$dias?>"> 
<?
                        } else {
?>
                        <tr style="background-color : #87CEEB; cursor : pointer;<?=$dias?>"> 
<?

                        }
?>
                          <td width="20" align="center" onclick="envia_pendencia('<?=$pendente["ID_SOLICITACAO"]?>','<?=$pendente["ID_CIDADE"]?>','<?=$pendente["ID_TIPO_SOLICITACAO"]?>')"><?=$pendente["DT_SOLICITACAOS"]?></td>
                          <td  onclick="envia_pendencia('<?=$pendente["ID_SOLICITACAO"]?>','<?=$pendente["ID_CIDADE"]?>','<?=$pendente["ID_TIPO_SOLICITACAO"]?>')"><?=$pendente["NM_EDIFICACOES_LX"]?></td>
                          <td align="center" onclick="envia_pendencia('<?=$pendente["ID_SOLICITACAO"]?>','<?=$pendente["ID_CIDADE"]?>','<?=$pendente["ID_TIPO_SOLICITACAO"]?>')"><?=$pendente["NM_CIDADE"]?></td>
                          <? if ($global_exclusao!="N") { ?>
                          <td align="center" onclick="excluir('<?=$pendente["ID_SOLICITACAO"]?>','<?=$pendente["ID_CIDADE"]?>','<?=$pendente["ID_TIPO_SOLICITACAO"]?>')">
                            <? if($pendente["ID_USUARIO"]=='solicitacao') { ?>
    	                        <img src="./imagens/b_drop_i.png" alt="" align="middle" border="0">
                            <? } else { ?>
	                            <img src="./imagens/b_drop.png" alt="" align="middle" border="0">
                            <? } ?>
                          </td>
                          <? } ?>
                        </tr>
<?
                        $cont++;
                      }
                    } else {
?>
                        <tr>
                          <td width="20" align="center">00/00/0000</td>
                          <td>Nenhuma Solicita��o Encontrada</td>
                        </tr>
<?
                    }
if ($diasd) {
?>
                        <tr style="background-color : <?=COR_BARRA01?>; ">
                          <td colspan="4" align="center"><b>Os campos em vermelho est�o pendentes a mais de 20 dias</b></td>
                        </tr>
<?
}
?>

                  </table>
                </fieldset>
                </td>
              </tr>
            </table>
          </form>

