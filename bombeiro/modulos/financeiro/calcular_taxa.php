<?  //echo "<pre>get: "; print_r($_GET); echo "post: "; print_r($_POST); echo "</pre>";

    require 'lib/loader.php';
    
    foreach ($_GET as $i => $v) $$i = $v;
    foreach ($_POST as $i => $v) $$i = $v;

    $conn = new BD (BD_HOST, BD_USER, BD_PASS, BD_NOME_ACESSOS);
    if($conn->get_status()==false) die($conn->get_msg());

    $rotulo = array (
        'NM_CONTATO' => 'Contato',
        'NM_RAZAO_SOCIAL' => 'Raz&atilde;o Social',
        'NM_PROPRIETARIO' => 'Propriet&aacute;rio',
        'NM_FANTASIA' => 'Nome Fantasia',
        'VL_AREA_CONSTRUIDA' => '&Aacute;rea constru&iacute;da',
        'CH_PROTOCOLADO' => 'Protocolado',
        'DT_SOLICITACAO' => 'Data da solicita&ccedil;&atilde;o',
        'NM_CIDADE' => 'Cidade',
        'VL_AREA_DESC_VISTORIAS' => '&Aacute;rea vistoriada'
    );
    $sql = "SELECT ".
        TBL_SOL_FUNC.".NM_RAZAO_SOCIAL, ".
        TBL_SOL_FUNC.".NM_PROPRIETARIO, ".
        TBL_SOL_FUNC.".NM_CONTATO, ".
        TBL_SOL_FUNC.".NR_FONE_PROPRIETARIO, ".
        TBL_SOL_FUNC.".NM_FANTASIA, ".
        TBL_SOL_FUNC.".VL_AREA_CONSTRUIDA, ".
        TBL_SOL_FUNC.".CH_PROTOCOLADO, ".
        "DATE_FORMAT(".TBL_SOL_FUNC.".DT_SOLICITACAO,'%d/%m/%Y') AS DT_SOLICITACAO, ".
        TBL_CIDADE.".NM_CIDADE,  ".
        "SUM(".TBL_DESC_FUNC.".VL_AREA_DESC_FUNC) AS VL_AREA_DESC_VISTORIAS  ".
    "FROM ".TBL_SOL_FUNC." ".
        "LEFT JOIN ".TBL_CIDADE." ON(".TBL_SOL_FUNC.".ID_CIDADE = ".TBL_CIDADE.".ID_CIDADE)  ".
        "LEFT JOIN ".TBL_DESC_FUNC." ON(".
            TBL_SOL_FUNC.".ID_SOLIC_FUNC = ".TBL_DESC_FUNC.".ID_SOLIC_FUNC AND ".
            TBL_SOL_FUNC.".ID_TP_FUNC = ".TBL_DESC_FUNC.".ID_TP_FUNC AND ".
            TBL_SOL_FUNC.".ID_CIDADE = ".TBL_DESC_FUNC.".ID_CIDADE) ".
    "WHERE ".
        TBL_SOL_FUNC.".ID_SOLIC_FUNC = '$id_solic_func' and ".
        TBL_SOL_FUNC.".ID_CIDADE = '$id_cidade'
    GROUP BY ".
        TBL_SOL_FUNC.".ID_SOLIC_FUNC, ".
        TBL_SOL_FUNC.".ID_TP_FUNC, ".
        TBL_SOL_FUNC.".ID_CIDADE " .
    ";"; // echo "sql: $sql";
    $conn->query($sql);
    if ($r = $conn->fetch_row()) {
        foreach ($r as $i => $v) {
            switch ($i) {
                case 'CH_PROTOCOLADO':
                    if ($v = 'P') $r[$i] = 'protocolado'; 
                    if ($v = 'V') $r[$i] = 'vistoriado'; 
                break;
            }
        }
        $dados['solicitacao'] = $r;
    }

    // Servicos
    $sql = "SELECT ".
        TBL_SERVICO.".ID_SERVICO, ".
        TBL_SERVICO.".NM_SERVICO ".
    "FROM ".TBL_SERVICO." ".
    "WHERE ".
        TBL_SERVICO.".ID_CIDADE = $id_cidade AND ".
        TBL_SERVICO.".CH_OPERACAO IN ('F','T') " .
    ";";  //echo "sql: $sql";
    $conn->query($sql);
    while ($r = $conn->fetch_row()) $dados['servicos'][] = $r;

    if ($hdn_operador == 'calcular_taxa') {

        // Tipo de servico
        $sql = "SELECT ".
            TBL_TP_SERVICO.".ID_TP_SERVICO, ".
            TBL_TP_SERVICO.".NM_TP_SERVICO ".
        "FROM ".TBL_TP_SERVICO." ".
        "WHERE ".
            TBL_TP_SERVICO.".ID_CIDADE = $id_cidade AND ".
            TBL_TP_SERVICO.".ID_TP_SERVICO = $cmb_id_tp_servico " .
        ";";  //echo "sql: $sql";
        $conn->query($sql);
        while ($r = $conn->fetch_row()) $dados['tp_servico'] = $r;

        // Calcular a taxa
        $sql = "SELECT ".
            TBL_FORMULA.".NR_MAX_PARCELA, ".
            TBL_FORMULA.".NR_PRAZO_VENCTO, ".
            TBL_FORMULA.".DE_FORMULA, ".
            TBL_FORMULA.".VL_MIN_PARCELA, ".
            TBL_FORMULA.".VL_MAX_PARCELA ".
        "FROM ".TBL_FORMULA." ".
        "WHERE ".
            TBL_FORMULA.".ID_CIDADE = $id_cidade AND ".
            TBL_FORMULA.".ID_TP_SERVICO = $cmb_id_tp_servico AND ".
            TBL_FORMULA.".ID_SERVICO = $cmb_id_servico ".
            //TBL_FORMULA.".VL_MIN_AREA <= $dados[solicitacao][VL_AREA_DESC_VISTORIAS] AND ".
            //TBL_FORMULA.".VL_MAX_AREA >= $dados[solicitacao][VL_AREA_DESC_VISTORIAS] ".
        ";"; // echo "sql: $sql";

        if ($rbx_formula) {
            $VL_AREA = $hdn_area_vistoriada;
            eval($rbx_formula.";");
        }

        $conn->query($sql);
        while ($r = $conn->fetch_row()) $dados['formulas'][] = $r;
        
    }

    //echo "<pre>"; print_r($dados); echo "</pre>"; 

?>
<html>
<head>
<title>CBMSC/DiTI - Taxa para Vistoria de Funcionamento</title>
<link rel="stylesheet" type="text/css" href="../../css/sigat.css">
<script>

    function calcular_taxa2(f) {
        f.hdn_operador.value = "calcular_taxa";
        f.submit();
    }

    function carregar_taxa() {
        var f = window.opener.document.frm_baixa_projeto;
        f.txt_vl_total_cobrado_<?=$indice?>.value = form1.txt_valor_taxa.value;
    }

    function consultaSelc(formulario,cmb_campo,tabela,atrib,cond,obrigatorio,campo_atual,campos_limpos,novo) {
      //alert ('formulario: '+formulario+'\ncmb_campo: '+cmb_campo+'\ntabela: '+tabela+'\natrib: '+atrib+'\ncond: '+cond+'\nobrigatorio: '+obrigatorio+'\ncampo_atual: '+campo_atual+'\ncampos_limpos: '+campos_limpos+'\nnovo: '+novo);
      if ((campo_atual.value != "" )&&(campo_atual.value != 0)) {
        window.open("../../php/consultaSelc.php?formulario="+formulario+"&cmb_campo="+cmb_campo+"&tabela="+tabela+"&atrib="+atrib+"&cond="+cond+"&obrigatorio="+obrigatorio+"&novo="+novo,"consulsec","top=5000,left=5000,screenY=5000,screenX=5000,toolbar=no,location=no,directories=no,status=yes,menubar=no,scrollbars=no,resizable=no,width=1,height=1,innerwidth=1,innerheight=1");
      } else {
        var cmp = campos_limpos.split(",");
        for (var i=0;i<cmp.length;i++) {
          window.document.frm_prot_funcionamento[cmp[i]].options.length=0;
          sec_cmb=window.document.frm_prot_funcionamento[cmp[i]].options.length++;
          window.document.frm_prot_funcionamento[cmp[i]].options[sec_cmb].text='---------------';
          window.document.frm_prot_funcionamento[cmp[i]].options[sec_cmb].value='0';
        }
      }
    }

</script>
</head>
<body>
    <form name="form1" method="post">
        <input type="hidden" name="hdn_operador" value="">
        <input type="hidden" name="hdn_area_vistoriada" value="<?=$dados['solicitacao']['VL_AREA_DESC_VISTORIAS']?>">
        <table align="center" width="500" border="0">
            <? $colspan = 2; ?>
            <tr>
                <th colspan="<?=$colspan?>">Dados da Solicita&ccedil;&atilde;o</th>
            </tr>
            <? foreach ($dados['solicitacao'] as $i => $v) if ($rotulo[$i] and $v) { ?>
                <? if ($cor == '#ddeeff') $cor = '#ffffff'; else $cor = '#ddeeff'; ?>
                <tr>
                    <td bgcolor="<?=$cor?>" align="right" width="120"><?=$rotulo[$i]?>&nbsp;</td>
                    <td bgcolor="<?=$cor?>"><?=$v?></td>
                </tr>
            <? } ?>

            <!-- Servico -->
            <? if ($cor == '#ddeeff') $cor = '#ffffff'; else $cor = '#ddeeff'; ?>
            <tr>
                <td bgcolor="<?=$cor?>" align="right">Servi&ccedil;o&nbsp;</td>
                <td bgcolor="<?=$cor?>">
                    <? if ($_POST['cmb_id_servico']) {
                        foreach ($dados['servicos'] as $v2) 
                            if ($v2['ID_SERVICO'] == $_POST['cmb_id_servico']) {
                                echo $v2['NM_SERVICO'];
                                ?><input type="hidden" name="cmb_id_servico" value="<?=$v2['ID_SERVICO']?>"><?
                            }
                    } else { ?>
                        <select name="cmb_id_servico" class="campo_obr"  
onchange="consultaSelc(this.form.name,'cmb_id_tp_servico','<?=TBL_TP_SERVICO?>','ID_TP_SERVICO,NM_TP_SERVICO','ID_SERVICO='+this.value+' AND ID_CIDADE='+<?=$id_cidade?>,'s',this,'cmb_id_tp_servico',''); cmb_id_tp_servico.value='adf';">
                            <option value=""> - - - - - - escolha o servi&ccedil;o - - - - - - </option>
                            <? foreach ($dados['servicos'] as $v) { ?>
                                <option value="<?=$v['ID_SERVICO']?>"><?=$v['NM_SERVICO']?></option>
                            <? } ?>
                        </select>
                    <? } ?>
                </td>
            </tr>

            <!-- Tipo de Servico -->
            <? if ($cor == '#ddeeff') $cor = '#ffffff'; else $cor = '#ddeeff'; ?>
            <tr>
                <td bgcolor="<?=$cor?>" align="right">Tipo de servi&ccedil;o&nbsp;</td>
                <td bgcolor="<?=$cor?>">
                    <? if ($_POST['cmb_id_tp_servico']) {
                        echo $dados['tp_servico']['NM_TP_SERVICO'];
                        ?><input type="hidden" name="cmb_id_tp_servico" value="<?=$_POST['cmb_id_tp_servico']?>"><?
                    } else { ?>
                        <select name="cmb_id_tp_servico" class="campo_obr" title="Tipo de Serviço a Ser Prestado" onblur="calcular_taxa2(this.form);">
                            <option value=""> - - - - - - </option>
                        </select>
                    <? } ?>
                </td>
            </tr>

            <!-- Formulas -->

            <? if ($cor == '#ddeeff') $cor = '#ffffff'; else $cor = '#ddeeff'; ?>
            <tr>
                <td bgcolor="<?=$cor?>" align="right" valign="top">F&oacute;rmulas&nbsp;</td>
                <td bgcolor="<?=$cor?>">
                    <? foreach ($dados['formulas'] as $i => $v) { ?>
                        <? if ($rbx_formula == $v['DE_FORMULA']) $checked = 'checked="checked"'; else $checked = ''; ?>
                        <input type="radio" <?=$checked?> name="rbx_formula" value="<?=$v['DE_FORMULA']?>" class="campo" onchange="calcular_taxa2(this.form);" >&nbsp;<?=str_replace('$','',$v['DE_FORMULA'])?><br>
                    <? } ?>
                </td>
            </tr>

            <!-- Valor calculado -->

            <? if ($cor == '#ddeeff') $cor = '#ffffff'; else $cor = '#ddeeff'; ?>
            <tr>
                <td bgcolor="<?=$cor?>" align="right">Valor calculado&nbsp;</td>
                <td bgcolor="<?=$cor?>">
                    <input type="text" name="txt_valor_taxa" value="<?=number_format($RESULTADO,2,',','.')?>" class="campo" >
                </td>
            </tr>

            <tr>
                <td valign="center" colspan="<?=$colspan?>" align="center"><br>
                    <input type="button" name="btn_voltar" value="Voltar" class="botao"  onclick="history.back();">
                    <input type="button" name="btn_enviar" value="OK" class="botao"  onclick="carregar_taxa(); window.close();" >
                </td>
            </tr>
        </table>

    </form>
</body>
</html>