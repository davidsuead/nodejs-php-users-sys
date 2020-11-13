<?php

date_default_timezone_set('America/Sao_Paulo');
error_reporting(0);
if (isset($_GET['arquivo'])) {
    $logs = file('../var/logapi/' . $_GET['arquivo'] . '.log');
    if ($logs === false) {
        echo '<br><br>';
        echo "<div style='width: 100%; text-align: center;'>Arquivo não encontrado!</div>";
    } else {
        echo "<div style='width: 100%; text-align: center;'>";
        echo '<span style="font-size:36px;">Dados Recebidos</span>';
        echo '<br>';
        echo '<br>';
        echo "<a href='logs.php'>Voltar para Lista</a>";
        echo '<br>';
        echo '<br>';
        $dados = [];
        if (count($logs) > 0) {
            foreach ($logs as $key => $log) {
                echo $log;
            }
        }
    }
} else {
    echo "<div style='width: 100%; text-align: center; font-size:36px;'>Logs API</div>";
    echo '<br>';
    echo "<table width='100%' border='1' cellpadding='2' cellspacing='0'>";
    echo '<tr style="background-color: #000000; color:#FFFFFF;">';
    echo '<td>';
    echo '<b>Arquivo</b>';
    echo '</td>';
    echo '</tr>';
    $pasta = '../var/logapi/';
    if (is_dir($pasta)) {
        $diretorio = dir($pasta);
        $i = 1;
        $arrayArquivos = [];
        while (($arquivo = $diretorio->read()) !== false) {
            if (substr($arquivo, -3) == "log") {
                $arrayArquivos[date('Y/m/d H:i:s', filemtime($pasta . $arquivo))] = $arquivo;
            }
        }
        $diretorio->close();
        krsort($arrayArquivos, SORT_STRING);
        if (count($arrayArquivos) > 0) {
            foreach ($arrayArquivos as $key => $arquivo) {
                if ($i % 2 == 0) {
                    echo '<tr style="background-color: #E7E7E7; font-size:13px;">';
                } else {
                    echo '<tr style="background-color: #ffffff; font-size:13px;">';
                }
                $nomeArquivo = str_replace('.log', '', $arquivo);
                echo '<td>';
                echo "<a href='?arquivo={$nomeArquivo}' style='text-decoration:none; color:#000000;'>";
                echo $key." - ".$nomeArquivo;
                echo '</a>';
                echo '</td>';
                echo '<tr>';
                $i++;
            }
        }
    } else {
        echo '<tr style="background-color: #ffffff; font-size:13px;">';
        echo '<td>';
        echo "Pasta não existe";
        echo '</td>';
        echo '<tr>';
    }
    echo '</table>';
}

function getConverteDataBr($data, $hora = false) {
    $novadata = substr($data, 8, 2) . "/" . substr($data, 5, 2) . "/" . substr($data, 0, 4);
    if ($hora == true) {
        $novadata .= " " . substr($data, 11);
    }
    return $novadata;
}

?>