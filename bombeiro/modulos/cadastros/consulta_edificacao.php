<?
//echo "ok";exit;

//echo " <pre> "; print_r($_POST); echo "</pre>";

/*$sql= "select * from CIDADE ";
$res = mysql_query($sql);
while ($r = mysql_fetch_assoc($res)) {
 $dados[] = $r;
    }
echo "<pre>";print_r($dados); echo "</pre>"; */

// $campos_preenchidos = true;
// foreach ($_POST as $indice => $valor){
//     if ($valor == null and $indice != 'txt_re' and $indice != 'hdn_id_cidade') {
//         $campos_preenchidos = false;
//     }
// }

    require './../../requires/loader.php';
    require_once 'lib/loader.php';

    $arquivo = "edificacoes.php";
    $conn = new BD (BD_HOST, BD_USER, BD_PASS, BD_NOME_ACESSOS);
    if ($conn->get_status()==false) die($conn->get_msg());
    $sql = "SELECT ID_ROTINA, NM_ROTINA FROM ".TBL_ROTINAS." WHERE NM_ARQ_ROTINA ='".$arquivo."'";
    $conn->query($sql);
    $rows_rotina=$conn->num_rows();
    if ($rows_rotina>0) $rotina = $conn->fetch_row();

    $global_obj_sessao->load($rotina["ID_ROTINA"]);
    $perfil = $global_obj_sessao->is_perfiled_in();

    $ID_CIDADE = $_GET["campo1"];
    $ID_EDIFICACAO = $_GET["campo2"];

    if ($ID_EDIFICACAO && $ID_CIDADE) {

        $sql = "SELECT ID_CIDADE, ID_EDIFICACAO, CPF_CNPJ, NM_EDIFICACAO, NM_FANTASIA, NR, AREA_TOTAL, NR_ALTURA, TIPO, NR_PAVIMENTO, AREA_PAVIMENTO, NR_BLOCO, RISCO, SITUACAO, OCUPACAO, NR_CEP, NM_LOGRADOURO, NM_PROPRIETARIO, FONE, EMAIL, NM_BAIRRO FROM EDIFICACOES.EDIFICACOES WHERE ID_CIDADE = $ID_CIDADE AND ID_EDIFICACAO = $ID_EDIFICACAO";
	echo "sql: $sql";
        $res = mysql_query($sql);
	if ($r = mysql_fetch_assoc($res)) {
	    $edificacao = $r;
	}

	//echo "<pre>"; print_r($edificacao); echo "</pre>";

    }

if ($edificacao) { ?>
	<script language="javascript" type="text/javascript">
		var frm = window.opener.document.frm_edificacao;
		frm.txt_cpf.value="<?=$edificacao['CPF_CNPJ']?>";
		frm.txt_nm_proprietario.value="<?=$edificacao['NM_PROPRIETARIO']?>";
		frm.txt_fone.value="<?=$edificacao['FONE']?>";
		frm.txt_email.value="<?=$edificacao['EMAIL']?>";
		frm.txt_nm_edificacao.value="<?=$edificacao['NM_EDIFICACAO']?>";
		frm.txt_nm_fantasia.value="<?=$edificacao['NM_FANTASIA']?>";
		frm.txt_nm_logradouro.value="<?=$edificacao['NM_LOGRADOURO']?>";
		frm.txt_nr.value="<?=$edificacao['NR']?>";
		frm.cmb_id_cidade.value="<?=$edificacao['ID_CIDADE']?>";
		frm.txt_nm_bairro.value="<?=$edificacao['NM_BAIRRO']?>";
		frm.txt_cep.value="<?=$edificacao['NR_CEP']?>";
		frm.txt_area_total.value="<?=$edificacao['AREA_TOTAL']?>";
		frm.txt_altura.value="<?=$edificacao['NR_ALTURA']?>";
		frm.txt_area_pavimento.value="<?=$edificacao['AREA_PAVIMENTO']?>";
		frm.cmb_ocupacao.value="<?=$edificacao['OCUPACAO']?>"; 
		frm.cmb_risco.value="<?=$edificacao['RISCO']?>";
		frm.cmb_situacao.value="<?=$edificacao['SITUACAO']?>";
		frm.cmb_construcao.value="<?=$edificacao['TIPO']?>";
		frm.btn_enviar.value="Alterar";
		window.close();
	</script>	
<? } else { ?>
	<script language="javascript" type="text/javascript">
		var frm = window.opener.document.frm_edificacao;
		frm.btn_enviar.value="Inserir";
		frm.reset();
		window.opener.alert('Registro n�o encontrado');
		window.close();
	</script>	
<? } ?>
