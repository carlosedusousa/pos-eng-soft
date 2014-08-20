<?  
    require "lib/loader.php";

    $conn = new BD (BD_HOST, BD_USER, BD_PASS, BD_NOME_PROJETOS);

    $id_prot_func = $_GET['protocolo'];
    $formulario = $_GET['formulario'];
    $id_cidade = $_GET['cidade'];

    $sql = "SELECT ID_VISTORIA_FUNC FROM ".TBL_VISTORIA_FUNC . " WHERE ID_PROT_FUNC = '$id_prot_func' AND ID_CIDADE = '$id_cidade' ;"; 
    $conn->query($sql);
    while ($r = $conn->fetch_row()) $id_vistoria = $r['ID_VISTORIA_FUNC'];

    $sql = "SELECT ".
        TBL_SOL_FUNC.".ID_SOLIC_FUNC,".
        TBL_SOL_FUNC.".DT_SOLICITACAO,".
        TBL_SOL_FUNC.".NM_RAZAO_SOCIAL,".
        TBL_SOL_FUNC.".NM_FANTASIA_EMPRESA,".
        TBL_SOL_FUNC.".NM_CONTATO,".
        TBL_SOL_FUNC.".NR_FONE_EMPRESA,".
        TBL_SOL_FUNC.".DE_EMAIL_EMPRESA,".
        TBL_SOL_FUNC.".NM_EDIFICACOES,".
        TBL_SOL_FUNC.".NM_FANTASIA_EMPRESA,".
        TBL_SOL_FUNC.".ID_TP_LOGRADOURO,".
        TBL_SOL_FUNC.".NM_LOGRADOURO,".
        TBL_SOL_FUNC.".NR_EDIFICACOES,".
        TBL_SOL_FUNC.".ID_CIDADE,".
        TBL_SOL_FUNC.".NM_BAIRRO,".
        TBL_SOL_FUNC.".NR_CEP,".
        TBL_SOL_FUNC.".NM_COMPLEMENTO,".
        TBL_SOL_FUNC.".VL_AREA_CONSTRUIDA,".
        TBL_SOL_FUNC.".ID_OCUPACAO,".
        TBL_SOL_FUNC.".ID_RISCO,".
        TBL_SOL_FUNC.".ID_SITUACAO,".
        TBL_SOL_FUNC.".ID_TP_CONSTRUCAO,".
        TBL_SOL_FUNC.".NR_PAVIMENTOS,".
        TBL_SOL_FUNC.".NR_BLOCOS,".
        TBL_SOL_FUNC.".NR_CNPJ_EMPRESA,".
        TBL_SOL_FUNC.".CH_TP_FUNC,".
        TBL_SOL_FUNC.".NM_PROPRIETARIO,".
        TBL_SOL_FUNC.".NR_CNPJ_CPF_PROPRIETARIO,".
        TBL_SOL_FUNC.".NR_FONE_PROPRIETARIO,".
        TBL_SOL_FUNC.".DE_EMAIL_PROPRIETARIO,".
        TBL_CEP.".ID_CEP, ".
        "SUM(".TBL_ESTABELECIMENTO.".VL_AREA) AS VL_AREA_VISTORIADA,".
        TBL_TP_LOGRADOURO.".NM_TP_LOGRADOURO, ".
        TBL_USUARIO.".NM_USUARIO, ".
        TBL_USUARIO.".ID_USUARIO, " . 
        "(SELECT NM_CIDADE FROM ".TBL_CIDADE." WHERE ID_CIDADE=".TBL_USUARIO.".ID_CIDADE) AS NM_CIDADE_LOTACAO " .
    "FROM ".TBL_VISTORIA_FUNC." " . 
        "LEFT JOIN ".TBL_PROT_FUNC." ON (".
            TBL_VISTORIA_FUNC.".ID_PROT_FUNC=".TBL_PROT_FUNC.".ID_PROT_FUNC AND " .
            TBL_VISTORIA_FUNC.".ID_CIDADE=".TBL_PROT_FUNC.".ID_CIDADE) " .
        "LEFT JOIN ".TBL_VIST_ESTAB." ON (".
            TBL_VISTORIA_FUNC.".ID_VISTORIA_FUNC=".TBL_VIST_ESTAB.".ID_VISTORIA_FUNC AND " .
            TBL_VISTORIA_FUNC.".ID_CIDADE=".TBL_VIST_ESTAB.".ID_CIDADE_VISTORIA) " .
        "LEFT JOIN ".TBL_ESTABELECIMENTO." ON (".
            TBL_VIST_ESTAB.".ID_EDIFICACAO=".TBL_ESTABELECIMENTO.".ID_EDIFICACAO AND ".
            TBL_VIST_ESTAB.".ID_ESTABELECIMENTO=".TBL_ESTABELECIMENTO.".ID_ESTABELECIMENTO AND ".
            TBL_VIST_ESTAB.".ID_CIDADE_ESTAB=".TBL_ESTABELECIMENTO.".ID_CIDADE) " .
        "LEFT JOIN ".TBL_EDIFICACAO." ON (".
            TBL_ESTABELECIMENTO.".ID_EDIFICACAO=".TBL_EDIFICACAO.".ID_EDIFICACAO AND ".
            TBL_ESTABELECIMENTO.".ID_CIDADE=".TBL_EDIFICACAO.".ID_CIDADE) " .
        "LEFT JOIN ".TBL_CEP." ON (".
            TBL_EDIFICACAO.".ID_CEP=".TBL_CEP.".ID_CEP AND ".
            TBL_EDIFICACAO.".ID_LOGRADOURO=".TBL_CEP.".ID_LOGRADOURO AND ".
            TBL_EDIFICACAO.".ID_CIDADE_CEP=".TBL_CEP.".ID_CIDADE) " .
        "LEFT JOIN ".TBL_LOGRADOURO." ON(".
            TBL_CEP.".ID_LOGRADOURO=".TBL_LOGRADOURO.".ID_LOGRADOURO AND ".
            TBL_CEP.".ID_CIDADE=".TBL_LOGRADOURO.".ID_CIDADE) " .
        "LEFT JOIN ".TBL_TP_LOGRADOURO." ON(".TBL_LOGRADOURO.".ID_TP_LOGRADOURO=".TBL_TP_LOGRADOURO.".ID_TP_LOGRADOURO) " .
        "LEFT JOIN ".TBL_USUARIO." ON(".TBL_VISTORIA_FUNC.".ID_USUARIO=".TBL_USUARIO.".ID_USUARIO) " .
        "LEFT JOIN ".TBL_SOL_FUNC." ON(".
            TBL_PROT_FUNC.".ID_SOLIC_FUNC=".TBL_SOL_FUNC.".ID_SOLIC_FUNC AND ".
            TBL_PROT_FUNC.".ID_TP_FUNC=".TBL_SOL_FUNC.".ID_TP_FUNC AND ".
            TBL_PROT_FUNC.".ID_CIDADE=".TBL_SOL_FUNC.".ID_CIDADE) " .
    "WHERE ".
        TBL_VISTORIA_FUNC.".ID_VISTORIA_FUNC=$id_vistoria AND ".
        TBL_VISTORIA_FUNC.".ID_CIDADE=$id_cidade " .
    "GROUP BY ".
        TBL_VIST_ESTAB.".ID_VISTORIA_FUNC, ".
        TBL_VIST_ESTAB.".ID_CIDADE_VISTORIA " .
    ";";
    $conn->query($sql);
    if ($r = $conn->fetch_row()) $rs = $r; else echo "<script>window.opener.alert('Nenhum resgistro encontrado');</script><br>";

    foreach ($rs as $i => $v) $$i = $v;

        $sql = "select 
            ID_CIDADE, 
            ID_SOLIC_FUNC, 
            ID_TP_FUNC,
            ID_DESC_FUNC,
            NM_DESC_FUNC,
            NR_PAVIMENTO,
            NM_BLOCO,
            VL_AREA_DESC_FUNC
        from ".TBL_DESC_FUNC." " .
        "where " . 
            TBL_SOL_FUNC.".ID_SOLIC_FUNC = '$ID_SOLIC_FUNC' and ". 
            TBL_DESC_FUNC.".NR_PAVIMENTO = '0' and ". 
            TBL_DESC_FUNC.".ID_CIDADE = $id_cidade ". 
        ";";
        $conn->query($sql);
        while ($r = $conn->fetch_row()) $rs2[] = $r;
        if (!$rs2) {
            $sql = "select VL_AREA_DESC_FUNC from ".TBL_DESC_FUNC." where " . 
                TBL_SOL_FUNC.".ID_SOLIC_FUNC = '$ID_SOLIC_FUNC' and ". 
                TBL_DESC_FUNC.".NR_PAVIMENTO <> '0' and ". 
                TBL_DESC_FUNC.".ID_CIDADE = $id_cidade ". 
            ";";
            $conn->query($sql);
            while ($r = $conn->fetch_row()) $rs3[] = $r;
            $CH_TP_FUNC = 'T';
            $mesg .= "N&atilde;o existe salas cadastradas";
        }
    echo $mesg;

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <script language="JavaScript" type="text/javascript">
        var f = window.opener.document.<?=$formulario?>;
        var area_total = 0;
        var sec_cmb_desc_funcionamento = "";
        f.txt_nr_cnpj_empresa.value = '<?=$NR_CNPJ_EMPRESA?>';
        f.txt_nm_solicitante.value = '<?=$NM_RAZAO_SOCIAL?>';
        f.txt_nm_fantasia_empresa.value = '<?=$NM_FANTASIA_EMPRESA?>';
        f.txt_nm_contato.value = '<?=$NM_CONTATO?>';
        f.txt_nr_fone_empresa.value = '<?=$NR_FONE_EMPRESA?>';
        f.txt_de_email_empresa.value = '<?=$DE_EMAIL_EMPRESA?>';
        f.txt_nm_edificacao.value = '<?=$NM_EDIFICACOES?>';
        f.txt_nm_fantasia.value = '<?=$NM_FANTASIA_EMPRESA?>';
        f.cmb_id_tp_prefixo.value = '<?=$ID_TP_LOGRADOURO?>';
        f.txt_nm_logradouro.value = '<?=$NM_LOGRADOURO?>';
        f.txt_nr_numero.value = '<?=$NR_EDIFICACOES?>';
        f.cmb_id_cidade.value = '<?=$ID_CIDADE?>';
        f.txt_nm_bairro.value = '<?=$NM_BAIRRO?>';
        f.txt_nr_cep.value = '<?=$NR_CEP?>';
        f.txt_nm_complemento.value = '<?=$NM_COMPLEMENTO?>';
        f.txt_vl_area_tot_const.value = '<?=str_replace('.',',',$VL_AREA_CONSTRUIDA)?>';
        f.cmb_id_ocupacao.value = '<?=$ID_OCUPACAO?>';
        f.cmb_id_risco.value = '<?=$ID_RISCO?>';
        f.cmb_id_situacao.value = '<?=$ID_SITUACAO?>';
        f.cmb_id_tp_construcao.value = '<?=$ID_TP_CONSTRUCAO?>';
        f.cmb_nr_pavimentos.value = '<?=$NR_PAVIMENTOS?>';
        f.cmb_nr_blocos.value = '<?=$NR_BLOCOS?>';
        f.cmb_ch_tp_funcionamento.value = '<?=$CH_TP_FUNC?>';
        f.txt_nm_proprietario.value = '<?=$NM_PROPRIETARIO?>';
        f.txt_nr_cnpjcpf_proprietario.value = '<?=$NR_CNPJ_CPF_PROPRIETARIO?>';
        f.txt_fone_proprietario.value = '<?=$NR_FONE_PROPRIETARIO?>';
        f.txt_de_email_proprietario.value = '<?=$DE_EMAIL_PROPRIETARIO?>';

        f.cmb_desc_funcionamento.options.length = 0;

        if (f.cmb_ch_tp_funcionamento.value == "P") {

            f.cmb_desc_funcionamento.disabled = false;
            f.txt_nm_desc_funcionamento_tmp.disabled = false;
            f.txt_nm_bloco_desc_funcionamento_tmp.disabled = false;
            f.txt_vl_desc_funcionamento_tmp.disabled = false;

            sec_cmb_desc_funcionamento = f.cmb_desc_funcionamento.options.length++;
            f.cmb_desc_funcionamento.options[sec_cmb_desc_funcionamento].text = "escolha a sala";
            f.cmb_desc_funcionamento.options[sec_cmb_desc_funcionamento].value = "";

            <? foreach ($rs2 as $i=>$v) { ?>
                sec_cmb_desc_funcionamento = f.cmb_desc_funcionamento.options.length++;
                f.cmb_desc_funcionamento.options[sec_cmb_desc_funcionamento].text="<?=$v['NM_DESC_FUNC']?>";
                f.cmb_desc_funcionamento.options[sec_cmb_desc_funcionamento].value=sec_cmb_desc_funcionamento;
                f.hdn_nm_desc_funcionamento.value+='<?=$v['NM_DESC_FUNC']?>'+"^"; 
                f.hdn_vl_desc_funcionamento.value+='<?=$v['VL_AREA_DESC_FUNC']?>'+"^"; 
                f.hdn_nm_bloco_desc_funcionamento.value+='<?=$v['NM_BLOCO']?>'+"^";
                area_total += <?=$v['VL_AREA_DESC_FUNC'];?>;
            <? } ?>

        } else {

            sec_cmb_desc_funcionamento = f.cmb_desc_funcionamento.options.length++;
            f.cmb_desc_funcionamento.options[sec_cmb_desc_funcionamento].text = "__________________";
            f.cmb_desc_funcionamento.options[sec_cmb_desc_funcionamento].value = "";
            f.cmb_desc_funcionamento.disabled=true;
            f.txt_nm_desc_funcionamento_tmp.disabled=true;
            f.txt_nm_bloco_desc_funcionamento_tmp.disabled=true;
            f.txt_vl_desc_funcionamento_tmp.disabled=true;
            f.hdn_nm_desc_funcionamento.value="";
            f.hdn_vl_desc_funcionamento.value="";
            f.hdn_nm_bloco_desc_funcionamento.value="";
            f.btn_incluir_desc.disabled=false;
            f.btn_incluir_desc;
            f.btn_incluir_desc.disabled=true;
            f.btn_excluir_desc.disabled=false;
            f.btn_excluir_desc;
            f.btn_excluir_desc.disabled=true;
            area_total += '<?=$rs3[0]['VL_AREA_DESC_FUNC'];?>';

        }

        f.txt_vl_tot_vistoria.value = area_total;
        window.close();

    </script>
</head>
</html>