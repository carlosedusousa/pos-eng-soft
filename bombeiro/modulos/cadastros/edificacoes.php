<?
// echo " <pre> "; print_r($_POST); echo "</pre>";

/*$sql= "select * from CIDADE ";
$res = mysql_query($sql);
while ($r = mysql_fetch_assoc($res)) {
 $dados[] = $r;
    }*/
//echo "<pre>";print_r($POST); echo "</pre>"; exit;

$campos_preenchidos = true;
foreach ($_POST as $indice => $valor){
    if ($valor == null and $indice != 'txt_re' and $indice != 'cmb_pesq_id_cidade') {
        $campos_preenchidos = false;
    }
}

if ($campos_preenchidos) {
	$ID_CIDADE		=	($_POST["cmb_id_cidade"]);
	$ID_EDIFICACAO		=	($_POST["txt_id_edificacao"]);
	$RE      		=	($_POST["txt_re"]);
	$CPF_CNPJ		=	($_POST["txt_cpf"]);
	$NM_PROPRIETARIO	=	($_POST["txt_nm_proprietario"]);
	$FONE			=	($_POST["txt_fone"]);
	$EMAIL			=	($_POST["txt_email"]);
	$NM_EDIFICACAO		=	($_POST["txt_nm_edificacao"]);
	$NM_FANTASIA		=	($_POST["txt_nm_fantasia"]);
	$NM_LOGRADOURO		=	($_POST["txt_nm_logradouro"]);
	$NR_CEP			=	($_POST["txt_cep"]);
	$NM_BAIRRO		=	($_POST["txt_nm_bairro"]);
	$NR			=	($_POST["txt_nr"]);
	$AREA_TOTAL		=	($_POST["txt_area_total"]);
	$NR_ALTURA		=	($_POST["txt_altura"]);
	$AREA_PAVIMENTO		=	($_POST["txt_area_pavimento"]);
	$RISCO			=	($_POST["cmb_risco"]);
	$OCUPACAO		=	($_POST["cmb_ocupacao"]);
	$SITUACAO		=	($_POST["cmb_situacao"]);
	$TIPO			=	($_POST["cmb_construcao"]);
	$NR_BLOCO		=	($_POST["cmb_nr_blocos"]);
	$NR_PAVIMENTO		=	($_POST["cmb_nr_pavimentos"]);

	if($_POST["btn_enviar"] == 'Inserir') {
	
		$sql = "INSERT INTO EDIFICACOES (ID_CIDADE, ID_EDIFICACAO, CPF_CNPJ, NM_PROPRIETARIO, FONE, EMAIL, NM_EDIFICACAO, NM_FANTASIA, NM_LOGRADOURO, NR_CEP, NM_BAIRRO, NR, AREA_TOTAL, NR_ALTURA, AREA_PAVIMENTO, RISCO, OCUPACAO, SITUACAO, TIPO, NR_BLOCO, NR_PAVIMENTO) VALUES ('$ID_CIDADE', '$ID_EDIFICACAO', '$CPF_CNPJ', '$NM_PROPRIETARIO', '$FONE', '$EMAIL', '$NM_EDIFICACAO', '$NM_FANTASIA', '$NM_LOGRADOURO', '$NR_CEP', '$NM_BAIRRO', '$NR', '$AREA_TOTAL', '$NR_ALTURA', '$AREA_PAVIMENTO', '$RISCO', '$OCUPACAO', '$SITUACAO', '$TIPO', '$NR_BLOCO', '$NR_PAVIMENTO')";
		//echo "sql: $sql";
		$res = mysql_query($sql);
// 		echo "Edifica��o inserida com o n�: $ID_EDIFICACAO";
		?>
		<script language="javascript" type="text/javascript">
		alert("Registro inserido com sucesso");
		</script>
		<?
		?><meta http-equiv="refresh" content="0; index.php"><?
	
	} elseif($_POST["btn_enviar"] == 'Alterar') {

	$sql ="UPDATE EDIFICACOES SET CPF_CNPJ= '$CPF_CNPJ', NM_PROPRIETARIO= '$NM_PROPRIETARIO', FONE= '$FONE', EMAIL= '$EMAIL',  NM_EDIFICACAO='$NM_EDIFICACAO', NM_FANTASIA= '$NM_FANTASIA', NM_LOGRADOURO= '$NM_LOGRADOURO', NR_CEP= '$NR_CEP', NM_BAIRRO= '$NM_BAIRRO', NR= '$NR', AREA_TOTAL= '$AREA_TOTAL', NR_ALTURA= '$NR_ALTURA', AREA_PAVIMENTO= '$AREA_PAVIMENTO', RISCO= '$RISCO', OCUPACAO= '$OCUPACAO', SITUACAO= '$SITUACAO', TIPO= '$TIPO',  NR_BLOCO= '$NR_BLOCO', NR_PAVIMENTO= '$NR_PAVIMENTO' WHERE EDIFICACOES.ID_EDIFICACAO= '$RE' AND EDIFICACOES.ID_CIDADE= '$ID_CIDADE'";
		//echo "sql: $sql";
		$res = mysql_query($sql);
?>
<script language="javascript" type="text/javascript">
  alert("Registro alterado com sucesso");
</script>
<?
?><meta http-equiv="refresh" content="0; index.php"><?
	}

} else {
	echo "Favor preencher todos os campos.";
}

?>

<script language="javascript" type="text/javascript">

    function consultaReg(campo1,campo2,arq) {
      var frm=document.frm_edificacao;
            if ((campo1.value!="") && (campo2.value!="")) {
		window.open(arq+"?campo1="+campo1.value+"&campo2="+campo2.value,"janela","top=5000,left=5000,screenY= 5000,screenX=5000,toolbar=no,location=no,directories=no,status=yes,menubar=no,scrollbars=no,resizable=no,width=1,height=1,innerwidth=1,innerheight=1");
      }
    }
</script>

<form target="_self" enctype="multipart/form-data" method="post" name="frm_edificacao">
  <input type="hidden" name="op_menu" value="cad_edificacao">
  <table style="width: 100%; text-align: left;" border="0" cellpadding="2" cellspacing="2">
    <tr>
      <td cellspacing="2">
        <fieldset>
          <legend>Pesquisa</legend>
          <table>
            <tr>
              <td align="right">RE</td>
	       <td><input type="text" name="txt_re" size="15" maxlength="100" class="campo_obr"></td>
                   <td><select name="cmb_pesq_id_cidade" onchange="consultaReg(this,document.frm_edificacao.txt_re,'modulos/cadastros/consulta_edificacao.php')">
		   <option value="">SELECIONE A CIDADE</option>
                   <?
		    $sql= "select ID_CIDADE, NM_CIDADE from CADASTROS.CIDADE ";
		    $res = mysql_query($sql);
 		    while ($r = mysql_fetch_assoc($res)) {
                        ?><option value="<?=$r["ID_CIDADE"]?>"><?=$r["NM_CIDADE"]?></option><?
                    }
		    ?>
                 </select>
             </td>
          </tr>
          </table>
          </fieldset>
      </td>
    </tr>
    <tr>
      <td colspan="2" style="vertical-align: top;">
        <fieldset>
          <legend>Propriet&aacute;rio</legend>
            <table width="95%" cellspacing="2">
              <tr>
                <td align="right" nowrap="true" >Nome</td>
                <td><input type="text" name="txt_nm_proprietario" value="<?=$_POST['txt_nm_proprietario']?>" size="55" maxlength="100" class="campo_obr"></td>
                <td align="right" nowrap="true" >CNPJ/CPF</td>
                <td><input type="text" name="txt_cpf" value="<?=$_POST['txt_cpf']?>" size="15" maxlength="18"></td>
              </tr>
              <tr>
                <td align="right" nowrap="true" >E-mail</td>
                <td><input type="text" name="txt_email" value="<?=$_POST['txt_email']?>" size="55" maxlength="100" class="campo_obr" style="text-transform : none;"></td>
                <td align="right" nowrap="true" >Fone</td>
                <td><input type="text" name="txt_fone" size="19" maxlength="12" value="<?=$_POST['txt_fone']?>" class="campo_obr"></td>
	      </tr>
            </table>
        </fieldset>
      </td>
    </tr>
    <tr>
      <td colspan="2" style="vertical-align: top;">
        <fieldset>
          <legend>Edifica&ccedil;&atilde;o</legend>
          <TABLE width="90%" cellspacing="0" border="0" cellpadding="0">
            <tr>
              <TD align="right" nowrap="true" >Nome</TD>
              <TD align="right" nowrap="true" ><INPUT type="text" name="txt_nm_edificacao" size="30" maxlength="100" class="campo_obr"></td>
              <TD align="right" nowrap="true" >Nome Fantasia</TD>
              <TD align="right" nowrap="true" ><INPUT type="text" name="txt_nm_fantasia" size="30" maxlength="100" class="campo"></TD>
            </tr>
          </TABLE>
          <fieldset>
          <legend>Endere&ccedil;o</legend>
          <table cellspacing="0" border="0" cellpadding="2" width="80%">
            <tr>
              <td align="right" nowrap="true" >Logradouro</td>
              <td>
                <input type="text" name="txt_nm_logradouro" size="50" maxlength="100" class="campo_obr">
              </td>
              <td align="right" nowrap="true" >N�</td>
              <td>
                <input type="text" name="txt_nr" size="16" maxlength="6" class="campo">
              </td>
            </tr>
            <tr>
	      <td align="right" nowrap="true" >Cidade</td>
              <td><select name="cmb_id_cidade">
		   <option value="">__________________</option>
                   <?
		    $sql= "select ID_CIDADE, NM_CIDADE from CADASTROS.CIDADE ";
		    $res = mysql_query($sql);
 		    while ($r = mysql_fetch_assoc($res)) {
                        ?><option value="<?=$r["ID_CIDADE"]?>"><?=$r["NM_CIDADE"]?></option><?
                    }
		    ?>
                 </select>
             </td>
		<td align="right" nowrap="true" >CEP</td>
		<td><input type="text" name="txt_cep" size="16" maxlength="20" class="campo"></td>
           </tr>
	   <tr>
		<td align="right" nowrap="true" >Bairro</td>
		<td><input type="text" name="txt_nm_bairro" size="20" maxlength="50" class="campo_obr"></td>
                <td align="right" nowrap="true" >&Aacute;rea Total Construida</td>
                <td><input type="text" name="txt_area_total" size="20" maxlength="12" align="right" class="campo_obr"><em>(m�)</em>
                </td>
	   </tr>
          </table>
	</fieldset>
	<fieldset>
            <legend>Caracter&iacute;sticas</legend>
              <table width="95%" cellspacing="0" border="0" cellpadding="2">
                <tr>
		<td align="right" nowrap="true" >Altura</td>
                  <td><input type="text" name="txt_altura" size="20" maxlength="9" align="right" class="campo_obr"><em>(m)</em></td>
                  <td align="right" nowrap="true" >&Aacute;rea do Pavimento Tipo</td>
                  <td> <input type="text" name="txt_area_pavimento" size="20" maxlength="10" align="right" class="campo_obr" ><em>(m�)</em></td>
                </tr>
                <tr>
                  <td align="right" nowrap="true" >Ocupa&ccedil;&atilde;o</td>
                  <td><select name="cmb_ocupacao">
                        <option value="">___________________________________</option>
			<option value="Residencial privativa multifamiliar">Residencial privativa multifamiliar</option>
			<option value="Residencial privativa unifamiliar">Residencial privativa unifamiliar</option>
			<option value="Residencial coletiva">Residencial coletiva</option>
			<option value="Residencial transit&oacute;ria">Residencial transit&oacute;ria</option>
			<option value="Comercial">Comercial</option>
			<option value="Industrial">Industrial</option>
			<option value="Mista">Mista</option>
			<option value="P&uacute;blica">P&uacute;blica</option>
			<option value="Escolar">Escolar</option>
			<option value="Hospital e laboratorial">Hospital e laboratorial</option>
			<option value="Garagens">Garagens</option>
			<option value="Reuni&atilde;o de p&uacute;blico">Reuni&atilde;o de p&uacute;blico</option>
			<option value="Edifica&ccedil;&otilde;es especiais">Edifica&ccedil;&otilde;es especiais</option>
			<option value="Dep&oacute;sitos de inflam&aacute;veis">Dep&oacute;sitos de inflam&aacute;veis</option>
			<option value="Dep&oacute;sitos de explosivos e muni&ccedil;&otilde;es">Dep&oacute;sitos de explosivos e muni&ccedil;&otilde;es</option>
                      </select>
                  </td>
                  <td align="right" nowrap="true" >Risco</td>
                  <td>
                    <select name="cmb_risco" class="campo_obr">
                      <option value="">__________________</option>
			<option value="Leve">Leve</option>
			<option value="M&eacute;dio">M&eacute;dio</option>
			<option value="Elevado">Elevado</option>
                    </select>
                  </td>
                </tr>
                <tr>
                  <td align="right" nowrap="true" >Situa&ccedil;&atilde;o</td>
                  <td>
                    <select name="cmb_situacao" class="campo_obr">
                      <option value="">__________________</option>
                        <option value="Existente">Existente</option>
			<option value="Nova">Nova</option>
			<option value="Em constru&ccedil;&atilde;o">Em constru&ccedil;&atilde;o</option>
		   </select>
                  </td>
                  <td align="right" nowrap="true" >Tipo</td>
                  <td>
                    <select name="cmb_construcao">
                      <option value="">__________________</option>
			<option value="Alvenaria">Alvenaria</option>
			<option value="Madeira">Madeira</option>
			<option value="Mista">Mista</option>
			<option value="Met&aacute;lica">Met&aacute;lica</option>
                    </select>
                  </td>
                </tr>
                <tr>
                  <td align="right" nowrap="true" >N� Pavimentos</td>
                  <td>
                    <select name="cmb_nr_pavimentos" class="campo_obr">
                      <?
                        for ($i=1;$i<=50;$i++) {
                          echo "<option value=\"".$i."\">".$i."</option>\n";
                        }
                      ?>
		</select>
                  </td>
                  <td align="right" nowrap="true" >N� Blocos</td>
                  <td>
                    <select name="cmb_nr_blocos" class="campo_obr">
                      <?
                        for ($i=1;$i<=50;$i++) {
                          echo "<option value=\"".$i."\">".$i."</option>\n";
                        }
                      ?>
                    </select>
                  </td>
                </tr>
              </table>
          </fieldset>
	</fieldset>
        </fieldset>
      </tr>
    </tr>	
    <tr valign="top" align="center">
      <td>
        <table width="50%" cellspacing="0" border="0" cellpadding="0" align="center">
          <tr align="center" valign="center">
            <td>
              <input type="submit" name="btn_enviar" value="Inserir" align="middle" title="Confirma a Solicita&ccedil;&atilde;o de projeto">
            </td>
            <td>
              <input type="reset" name="btn_limpar" value="Limpar" align="middle" title="Limpa o formul&aacute;rio">
            </td>
          </tr>
        </table>
    </tr>
  </table>
  </form>
