<?php

namespace App\Http\Controllers\Erp;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\Supplier;
use App\Models\Municipio;



class ImportXmlController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('erp.importxml.index', ['msg' => '', 'msgtipo' => '']);
    }



    public function import(Request $request)
    {

        request()->validate([
            'file' => 'required'
        ]);

        $qtdArquivosXml = 0;
        $allowedfileExtension = ['xml'];
        $ten = auth()->user()->tenant_id;

        if ($request->hasfile('file')) {
            foreach ($request->file('file') as $file) {
                $extension = $file->getClientOriginalExtension();
                $check = in_array($extension, $allowedfileExtension);
                if ($check) {
                    $chaveNfe = $this->importaXml($file);
                    if (!empty($chaveNfe)) {
                        $qtdArquivosXml++;
                        Storage::disk('local')->put('/tennants/' . $ten . '/xmls/' . $chaveNfe . '.xml', file_get_contents($file));
                    }
                    // $folder = Storage::disk('local')->getAdapter()->getPathPrefix();
                    // $newUrl = Storage::url('oiooioioi.xml');
                    // Storage::move($folder . $nome, $folder . 'loloi.xml');
                    //exit();
                }
            }
        }

        if ($qtdArquivosXml > 0) {
            return view('erp.importxml.index', [
                'msg' => $qtdArquivosXml . ' arquivos importados com Sucesso',
                'msgtipo' => 'ok'
            ]);
        } else {
            return view('erp.importxml.index', [
                'msg' => 'Nenhum Arquivo foi Importado',
                'msgtipo' => 'Erro'
            ]);
        }
    } // function import





    public function importaXml($arqXml)
    {
        $xml = new \DOMDocument();
        $xml->load($arqXml);

        $ide = $xml->getElementsByTagName("ide");
        foreach ($ide as $ide) {

            $mod = $ide->getElementsByTagName("mod");
            $mod = $mod->item(0)->nodeValue;
            if ($mod != '55') {
                return;
            }

            $infProt = $xml->getElementsByTagName("infProt");
            foreach ($infProt as $infProt) {
                $chNFe = $infProt->getElementsByTagName("chNFe");
                $chNFe = $chNFe->item(0)->nodeValue;
                $xMotivo = $infProt->getElementsByTagName("xMotivo");
                $xMotivo = $xMotivo->item(0)->nodeValue;
            }

            if ($xMotivo != 'Autorizado o uso da NF-e') {
                return;
            }

            $cUF = $ide->getElementsByTagName("cUF");
            $cUF = $cUF->item(0)->nodeValue;
            $cNF = $ide->getElementsByTagName("cNF");
            $cNF = $cNF->item(0)->nodeValue;
            $natOp = $ide->getElementsByTagName("natOp");
            $natOp = $natOp->item(0)->nodeValue;
            $serie = $ide->getElementsByTagName("serie");
            $serie = $serie->item(0)->nodeValue;
            $nNF = $ide->getElementsByTagName("nNF");
            $nNF = $nNF->item(0)->nodeValue;
            $dhEmi = $ide->getElementsByTagName("dhEmi");
            $dhEmi = $dhEmi->item(0)->nodeValue;
            $dhSaiEnt = $ide->getElementsByTagName("dhSaiEnt");
            if ($dhSaiEnt->length > 0) {
                $dhSaiEnt = $dhSaiEnt->item(0)->nodeValue;
            } else {
                $dhSaiEnt = '';
            }

            $tpNF = $ide->getElementsByTagName("tpNF");
            $tpNF = $tpNF->item(0)->nodeValue;
            $idDest = $ide->getElementsByTagName("idDest");
            $idDest = $idDest->item(0)->nodeValue;
            $cMunFG = $ide->getElementsByTagName("cMunFG");
            $cMunFG = $cMunFG->item(0)->nodeValue;
            $tpImp = $ide->getElementsByTagName("tpImp");
            $tpImp = $tpImp->item(0)->nodeValue;
            $tpEmis = $ide->getElementsByTagName("tpEmis");
            $tpEmis = $tpEmis->item(0)->nodeValue;
            $cDV = $ide->getElementsByTagName("cDV");
            $cDV = $cDV->item(0)->nodeValue;
            $tpAmb = $ide->getElementsByTagName("tpAmb");
            $tpAmb = $tpAmb->item(0)->nodeValue;
            $finNFe = $ide->getElementsByTagName("finNFe");
            $finNFe = $finNFe->item(0)->nodeValue;
            $indFinal = $ide->getElementsByTagName("indFinal");
            $indFinal = $indFinal->item(0)->nodeValue;
            $indPres = $ide->getElementsByTagName("indPres");
            $indPres = $indPres->item(0)->nodeValue;
            $procEmi = $ide->getElementsByTagName("procEmi");
            $procEmi = $procEmi->item(0)->nodeValue;
            $verProc = $ide->getElementsByTagName("verProc");
            $verProc = $verProc->item(0)->nodeValue;

?>
            <table>
                <tr>
                    <th>INFORMAÃ‡Ã”ES DA NFe</th>
                </tr>
            </table>
            <table border="1" style="width:100%">
                <tr>
                    <td style="width:10%">cUF</td>
                    <td><?php echo $cUF; ?></td>
                </tr>
                <tr>
                    <td style="width:10%">cNF</td>
                    <td><?php echo $cNF; ?></td>
                </tr>
                <tr>
                    <td style="width:10%">natOp</td>
                    <td><?php echo $natOp; ?></td>
                </tr>
                <tr>
                    <td style="width:10%">mod</td>
                    <td><?php echo $mod; ?></td>
                </tr>
                <tr>
                    <td style="width:10%">serie</td>
                    <td><?php echo $serie; ?></td>
                </tr>
                <tr>
                    <td style="width:10%">nNF</td>
                    <td><?php echo $nNF; ?></td>
                </tr>
                <tr>
                    <td style="width:10%">dhEmi</td>
                    <td><?php echo $dhEmi; ?></td>
                </tr>
                <tr>
                    <td style="width:10%">dhSaiEnt</td>
                    <td><?php echo $dhSaiEnt; ?></td>
                </tr>
                <tr>
                    <td style="width:10%">tpNF</td>
                    <td><?php echo $tpNF; ?></td>
                </tr>
                <tr>
                    <td style="width:10%">idDest</td>
                    <td><?php echo $idDest; ?></td>
                </tr>
                <tr>
                    <td style="width:10%">cMunFG</td>
                    <td><?php echo $cMunFG; ?></td>
                </tr>
                <tr>
                    <td style="width:10%">tpImp</td>
                    <td><?php echo $tpImp; ?></td>
                </tr>
                <tr>
                    <td style="width:10%">tpEmis</td>
                    <td><?php echo $tpEmis; ?></td>
                </tr>
                <tr>
                    <td style="width:10%">cDV</td>
                    <td><?php echo $cDV; ?></td>
                </tr>
                <tr>
                    <td style="width:10%">tpAmb</td>
                    <td><?php echo $tpAmb; ?></td>
                </tr>
                <tr>
                    <td style="width:10%">finNFe</td>
                    <td><?php echo $finNFe; ?></td>
                </tr>
                <tr>
                    <td style="width:10%">indFinal</td>
                    <td><?php echo $indFinal; ?></td>
                </tr>
                <tr>
                    <td style="width:10%">indPres</td>
                    <td><?php echo $indPres; ?></td>
                </tr>
                <tr>
                    <td style="width:10%">procEmi</td>
                    <td><?php echo $procEmi; ?></td>
                </tr>
                <tr>
                    <td style="width:10%">verProc</td>
                    <td><?php echo $verProc; ?></td>
                </tr>
            </table>
        <?php

            /*echo nl2br(
            '######## INFORMAÃ‡Ã”ES DA NFe #######'. "\n".
            $cUF . "\n".
            $cNF . "\n".
            $natOp . "\n".
            $mod . "\n".
            $serie . "\n".
            $nNF . "\n".
            $dhEmi . "\n".
            $dhSaiEnt . "\n".
            $tpNF . "\n".
            $idDest . "\n".
            $cMunFG . "\n".
            $tpImp . "\n".
            $tpEmis . "\n".
            $cDV . "\n".
            $tpAmb . "\n".
            $finNFe . "\n".
            $indFinal . "\n".
            $indPres . "\n".
            $procEmi . "\n".
            $verProc . "\n"
            );*/
        }

        echo nl2br("\n");

        $emit = $xml->getElementsByTagName("emit");
        foreach ($emit as $emit) {
            $CNPJ = $emit->getElementsByTagName("CNPJ");
            $CNPJ = $CNPJ->item(0)->nodeValue;
            $xNome = $emit->getElementsByTagName("xNome");
            $xNome = $xNome->item(0)->nodeValue;
            $xFant = $emit->getElementsByTagName("xFant");
            if ($xFant->length > 0) {
                $xFant = $xFant->item(0)->nodeValue;
            } else {
                $xFant = '';
            }
            $xLgr = $emit->getElementsByTagName("xLgr");
            $xLgr = $xLgr->item(0)->nodeValue;
            $nro = $emit->getElementsByTagName("nro");
            $nro = $nro->item(0)->nodeValue;
            $xBairro = $emit->getElementsByTagName("xBairro");
            $xBairro = $xBairro->item(0)->nodeValue;
            $cMun = $emit->getElementsByTagName("cMun");
            $cMun = $cMun->item(0)->nodeValue;
            $xMun = $emit->getElementsByTagName("xMun");
            $xMun = $xMun->item(0)->nodeValue;
            $UF = $emit->getElementsByTagName("UF");
            $UF = $UF->item(0)->nodeValue;
            $CEP = $emit->getElementsByTagName("CEP");
            $CEP = $CEP->item(0)->nodeValue;
            $cPais = $emit->getElementsByTagName("cPais");
            $cPais = $cPais->item(0)->nodeValue;
            $xPais = $emit->getElementsByTagName("xPais");
            $xPais = $xPais->item(0)->nodeValue;
            $fone = $emit->getElementsByTagName("fone");
            $fone = $fone->item(0)->nodeValue;
            $IE = $emit->getElementsByTagName("IE");
            $IE = $IE->item(0)->nodeValue;
            $CRT = $emit->getElementsByTagName("CRT");
            $CRT = $CRT->item(0)->nodeValue;


            // salvar dados do fornecedor
            $supplierCnpj = Supplier::where('cnpj', intval($CNPJ))->first();
            if (!$supplierCnpj) {

                $supplier = new Supplier;
                $supplier->name = $xNome;
                $supplier->email = '???';
                $supplier->cep = intval($CEP);
                $supplier->street = $xLgr;
                $supplier->number = $nro;
                $supplier->compl = '';
                $supplier->phone = $fone;
                $supplier->celphone = '';
                $supplier->pfj = 'J';
                $municipio = Municipio::where('codigo', $cMun)->first();
                if($municipio){
                    $supplier->municipio_id = $municipio->id;
                }
                $supplier->cnpj = intval($CNPJ);
                $supplier->save();
            }


        ?>
            <table>
                <tr>
                    <th>INFORMAÃ‡Ã”ES DO EMITENTE</th>
                </tr>
            </table>
            <table border="1" style="width:100%">
                <tr>
                    <td style="width:10%">cUF</td>
                    <td><?php echo $cUF; ?></td>
                </tr>
                <tr>
                    <td style="width:10%">CNPJ</td>
                    <td><?php echo $CNPJ; ?></td>
                </tr>
                <tr>
                    <td style="width:10%">xNome</td>
                    <td><?php echo $xNome; ?></td>
                </tr>
                <tr>
                    <td style="width:10%">xFant</td>
                    <td><?php echo $xFant; ?></td>
                </tr>
                <tr>
                    <td style="width:10%">xLgr</td>
                    <td><?php echo $xLgr; ?></td>
                </tr>
                <tr>
                    <td style="width:10%">nro</td>
                    <td><?php echo $nro; ?></td>
                </tr>
                <tr>
                    <td style="width:10%">xBairro</td>
                    <td><?php echo $xBairro; ?></td>
                </tr>
                <tr>
                    <td style="width:10%">cMun</td>
                    <td><?php echo $cMun; ?></td>
                </tr>
                <tr>
                    <td style="width:10%">xMun</td>
                    <td><?php echo $xMun; ?></td>
                </tr>
                <tr>
                    <td style="width:10%">UF</td>
                    <td><?php echo $UF; ?></td>
                </tr>
                <tr>
                    <td style="width:10%">CEP</td>
                    <td><?php echo $CEP; ?></td>
                </tr>
                <tr>
                    <td style="width:10%">cPais</td>
                    <td><?php echo $cPais; ?></td>
                </tr>
                <tr>
                    <td style="width:10%">xPais</td>
                    <td><?php echo $xPais; ?></td>
                </tr>
                <tr>
                    <td style="width:10%">fone</td>
                    <td><?php echo $fone; ?></td>
                </tr>
                <tr>
                    <td style="width:10%">IE</td>
                    <td><?php echo $IE; ?></td>
                </tr>
                <tr>
                    <td style="width:10%">CRT</td>
                    <td><?php echo $CRT; ?></td>
                </tr>
            </table>
        <?php

            /*echo nl2br(
            "\n".'######## INFORMAÃ‡Ã”ES DO EMITENTE #######'. "\n".
            $cUF . "\n".
            $CNPJ . "\n".
            $xNome . "\n".
            $xFant . "\n".
            $xLgr . "\n".
            $nro . "\n".
            $xBairro . "\n".
            $cMun . "\n".
            $xMun . "\n".
            $UF . "\n".
            $CEP . "\n".
            $cPais . "\n".
            $xPais . "\n".
            $fone . "\n".
            $IE . "\n".
            $CRT . "\n"
            );*/
        }

        echo nl2br("\n");

        $dest = $xml->getElementsByTagName("dest");
        foreach ($dest as $dest) {
            $CNPJ = $dest->getElementsByTagName("CNPJ");
            if ($CNPJ->length > 0) {
                $CNPJ = $CNPJ->item(0)->nodeValue;
            } else {
                $CNPJ = '0';
            }
        $xNome = $dest->getElementsByTagName("xNome");
            $xNome = $xNome->item(0)->nodeValue;
            $xLgr = $dest->getElementsByTagName("xLgr");
            $xLgr = $xLgr->item(0)->nodeValue;
            $nro = $dest->getElementsByTagName("nro");
            $nro = $nro->item(0)->nodeValue;
            $xBairro = $dest->getElementsByTagName("xBairro");
            $xBairro = $xBairro->item(0)->nodeValue;
            $cMun = $dest->getElementsByTagName("cMun");
            $cMun = $cMun->item(0)->nodeValue;
            $xMun = $dest->getElementsByTagName("xMun");
            $xMun = $xMun->item(0)->nodeValue;
            $UF = $dest->getElementsByTagName("UF");
            $UF = $UF->item(0)->nodeValue;
            $CEP = $dest->getElementsByTagName("CEP");
            $CEP = $CEP->item(0)->nodeValue;
            $cPais = $dest->getElementsByTagName("cPais");
            $cPais = $cPais->item(0)->nodeValue;
            $xPais = $dest->getElementsByTagName("xPais");
            $xPais = $xPais->item(0)->nodeValue;
            $indIEDest = $dest->getElementsByTagName("indIEDestJ");
            if ($indIEDest->length > 0) {
                $indIEDest = $indIEDest->item(0)->nodeValue;
            } else {
                $indIEDest = '';
            }

            $IE = $dest->getElementsByTagName("IE");
            if ($IE->length > 0) {
                $IE = $IE->item(0)->nodeValue;
            } else {
                $IE = '';
            }

        ?>
            <table>
                <tr>
                    <th>INFORMAÃ‡Ã”ES DO DESTINATÃRIO / REMETENTE</th>
                </tr>
            </table>
            <table border="1" style="width:100%">
                <tr>
                    <td style="width:10%">CNPJ</td>
                    <td><?php echo $CNPJ; ?></td>
                </tr>
                <tr>
                    <td style="width:10%">xNome</td>
                    <td><?php echo $xNome; ?></td>
                </tr>
                <tr>
                    <td style="width:10%">xLgr</td>
                    <td><?php echo $xLgr; ?></td>
                </tr>
                <tr>
                    <td style="width:10%">nro</td>
                    <td><?php echo $nro; ?></td>
                </tr>
                <tr>
                    <td style="width:10%">xBairro</td>
                    <td><?php echo $xBairro; ?></td>
                </tr>
                <tr>
                    <td style="width:10%">cMun</td>
                    <td><?php echo $cMun; ?></td>
                </tr>
                <tr>
                    <td style="width:10%">xMun</td>
                    <td><?php echo $xMun; ?></td>
                </tr>
                <tr>
                    <td style="width:10%">UF</td>
                    <td><?php echo $UF; ?></td>
                </tr>
                <tr>
                    <td style="width:10%">CEP</td>
                    <td><?php echo $CEP; ?></td>
                </tr>
                <tr>
                    <td style="width:10%">cPais</td>
                    <td><?php echo $cPais; ?></td>
                </tr>
                <tr>
                    <td style="width:10%">xPais</td>
                    <td><?php echo $xPais; ?></td>
                </tr>
                <tr>
                    <td style="width:10%">indIEDest</td>
                    <td><?php echo $indIEDest; ?></td>
                </tr>
                <tr>
                    <td style="width:10%">IE</td>
                    <td><?php echo $IE; ?></td>
                </tr>
            </table>
        <?php

            /*echo nl2br(
            "\n".'######## INFORMAÃ‡Ã”ES DO DESTINATÃRIO / REMETENTE #######'. "\n".
            $CNPJ . "\n".
            $xNome . "\n".
            $xLgr . "\n".
            $nro . "\n".
            $xBairro . "\n".
            $cMun . "\n".
            $xMun . "\n".
            $UF . "\n".
            $CEP . "\n".
            $cPais . "\n".
            $xPais . "\n".
            $indIEDest . "\n".
            $IE . "\n"
            );*/
        }

        /*echo nl2br(
        "\n".'######## INFORMAÃ‡Ã•ES DOS PRODUTOS #######'. "\n");*/
        $i = 0;
        $det = $xml->getElementsByTagName("det");
        foreach ($det as $prod) {
            $i++;
            $cProd = $prod->getElementsByTagName("cProd");
            $cProd = $cProd->item(0)->nodeValue;
            $cEAN = $prod->getElementsByTagName("cEAN");
            $cEAN = $cEAN->item(0)->nodeValue;
            $xProd = $prod->getElementsByTagName("xProd");
            $xProd = $xProd->item(0)->nodeValue;
            $NCM = $prod->getElementsByTagName("NCM");
            $NCM = $NCM->item(0)->nodeValue;
            $CFOP = $prod->getElementsByTagName("CFOP");
            $CFOP = $CFOP->item(0)->nodeValue;
            $uCom = $prod->getElementsByTagName("uCom");
            $uCom = $uCom->item(0)->nodeValue;
            $qCom = $prod->getElementsByTagName("qCom");
            $qCom = $qCom->item(0)->nodeValue;
            $vUnCom = $prod->getElementsByTagName("vUnCom");
            $vUnCom = $vUnCom->item(0)->nodeValue;
            $vProd = $prod->getElementsByTagName("vProd");
            $vProd = $vProd->item(0)->nodeValue;
            $cEANTrib = $prod->getElementsByTagName("cEANTrib");
            $cEANTrib = $cEANTrib->item(0)->nodeValue;
            $uTrib = $prod->getElementsByTagName("uTrib");
            $uTrib = $uTrib->item(0)->nodeValue;
            $qTrib = $prod->getElementsByTagName("qTrib");
            $qTrib = $qTrib->item(0)->nodeValue;
            $vUnTrib = $prod->getElementsByTagName("vUnTrib");
            $vUnTrib = $vUnTrib->item(0)->nodeValue;
            $indTot = $prod->getElementsByTagName("indTot");
            $indTot = $indTot->item(0)->nodeValue;
            $vTotTrib = $prod->getElementsByTagName("vTotTrib");
            if ($vTotTrib->length > 0) {
                $vTotTrib = $vTotTrib->item(0)->nodeValue;
            } else {
                $vTotTrib = '';
            }

            echo nl2br("\n");

        ?>
            <table>
                <tr>
                    <th>INFORMAÃ‡Ã•ES DO PRODUTO <?php echo $i; ?></th>
                </tr>
            </table>
            <table border="1" style="width:100%">
                <tr>
                    <td style="width:10%">cProd</td>
                    <td><?php echo $cProd; ?></td>
                </tr>
                <tr>
                    <td style="width:10%">cEAN</td>
                    <td><?php echo $cEAN; ?></td>
                </tr>
                <tr>
                    <td style="width:10%">xProd</td>
                    <td><?php echo $xProd; ?></td>
                </tr>
                <tr>
                    <td style="width:10%">NCM</td>
                    <td><?php echo $NCM; ?></td>
                </tr>
                <tr>
                    <td style="width:10%">CFOP</td>
                    <td><?php echo $CFOP; ?></td>
                </tr>
                <tr>
                    <td style="width:10%">uCom</td>
                    <td><?php echo $uCom; ?></td>
                </tr>
                <tr>
                    <td style="width:10%">qCom</td>
                    <td><?php echo $qCom; ?></td>
                </tr>
                <tr>
                    <td style="width:10%">vUnCom</td>
                    <td><?php echo $vUnCom; ?></td>
                </tr>
                <tr>
                    <td style="width:10%">vProd</td>
                    <td><?php echo $vProd; ?></td>
                </tr>
                <tr>
                    <td style="width:10%">cEANTrib</td>
                    <td><?php echo $cEANTrib; ?></td>
                </tr>
                <tr>
                    <td style="width:10%">uTrib</td>
                    <td><?php echo $uTrib; ?></td>
                </tr>
                <tr>
                    <td style="width:10%">qTrib</td>
                    <td><?php echo $qTrib; ?></td>
                </tr>
                <tr>
                    <td style="width:10%">vUnTrib</td>
                    <td><?php echo $vUnTrib; ?></td>
                </tr>
                <tr>
                    <td style="width:10%">indTot</td>
                    <td><?php echo $indTot; ?></td>
                </tr>
                <tr>
                    <td style="width:10%">vTotTrib</td>
                    <td><?php echo $vTotTrib; ?></td>
                </tr>
            </table>
            <?php

            /*echo nl2br(
            $cProd . "\n".
            $cEAN  . "\n".
            $xProd . "\n".
            $NCM . "\n".
            $CFOP . "\n".
            $uCom . "\n".
            $qCom . "\n".
            $vUnCom . "\n".
            $vProd . "\n".
            $cEANTrib . "\n".
            $uTrib . "\n".
            $qTrib . "\n".
            $vUnTrib . "\n".
            $indTot . "\n".
            $vTotTrib . "\n"
            );*/

            $ICMS = $xml->getElementsByTagName("ICMS");
            foreach ($ICMS as $ICMS) {
                $orig = $ICMS->getElementsByTagName("orig");
                $orig = $orig->item(0)->nodeValue;
                $CST = $ICMS->getElementsByTagName("CST");
                if ($CST->length > 0) {
                    $CST = $CST->item(0)->nodeValue;
                } else {
                    $CST = '';
                }
                    $CSOSN = $ICMS->getElementsByTagName("CSOSN");
                if ($CSOSN->length > 0) {
                    $CSOSN = $CSOSN->item(0)->nodeValue;
                } else {
                    $CSOSN = '';
                }
                $vBCSTRet = $ICMS->getElementsByTagName("vBCSTRetd");
                if ($vBCSTRet->length > 0) {
                    $vBCSTRet = $vBCSTRet->item(0)->nodeValue;
                } else {
                    $vBCSTRet = '';
                }

                $pST = $ICMS->getElementsByTagName("pST");
                if ($pST->length > 0) {
                    $pST = $pST->item(0)->nodeValue;
                } else {
                    $pST = '';
                }

                $vICMSSubstituto = $ICMS->getElementsByTagName("vICMSSubstituto");
                if ($vICMSSubstituto->length > 0) {
                    $vICMSSubstituto = $vICMSSubstituto->item(0)->nodeValue;
                } else {
                    $vICMSSubstituto = '';
                }

                $vICMSSTRet = $ICMS->getElementsByTagName("vICMSSTRet");
                if ($vICMSSTRet->length > 0) {
                    $vICMSSTRet = $vICMSSTRet->item(0)->nodeValue;
                } else {
                    $vICMSSTRet = '';
                }
            }

            ?>
            <table>
                <tr>
                    <th>ICMS</th>
                </tr>
            </table>
            <table border="1" style="width:100%">
                <tr>
                    <td style="width:10%">orig</td>
                    <td><?php echo $orig; ?></td>
                </tr>
                <tr>
                    <td style="width:10%">CST</td>
                    <td><?php echo $CST; ?></td>
                </tr>
                <tr>
                    <td style="width:10%">CSOSN</td>
                    <td><?php echo $CSOSN; ?></td>
                </tr>
                <tr>
                    <td style="width:10%">vBCSTRet</td>
                    <td><?php echo $vBCSTRet; ?></td>
                </tr>
                <tr>
                    <td style="width:10%">pST</td>
                    <td><?php echo $pST; ?></td>
                </tr>
                <tr>
                    <td style="width:10%">vICMSSubstituto</td>
                    <td><?php echo $vICMSSubstituto; ?></td>
                </tr>
                <tr>
                    <td style="width:10%">vICMSSTRet</td>
                    <td><?php echo $vICMSSTRet; ?></td>
                </tr>
            </table>
            <?php

            /*echo nl2br(
            '######## ICMS #######'. "\n".
            $orig . "\n".
            $CST . "\n".
            $CSOSN . "\n".
            $vBCSTRet . "\n".
            $pST . "\n".
            $vICMSSubstituto . "\n".
            $vICMSSTRet . "\n"
            );*/

            $IPI = $xml->getElementsByTagName("IPI");
            foreach ($IPI as $IPI) {
                $cEnq = $IPI->getElementsByTagName("cEnq");
                $cEnq = $cEnq->item(0)->nodeValue;
                $CST = $IPI->getElementsByTagName("CST");
                $CST = $CST->item(0)->nodeValue;
                $vBC = $IPI->getElementsByTagName("vBC");
                if ($vBC->length > 0) {
                    $vBC = $vBC->item(0)->nodeValue;
                } else {
                    $vBC = '';
                }
                $pIPI = $IPI->getElementsByTagName("pIPI");
                if ($pIPI->length > 0) {
                    $pIPI = $pIPI->item(0)->nodeValue;
                } else {
                    $pIPI = '';
                }
                $vIPI = $IPI->getElementsByTagName("vIPI");
                if ($vIPI->length > 0) {
                    $vIPI = $vIPI->item(0)->nodeValue;
                } else {
                    $vIPI = '';
                }
            }

            ?>
            <table>
                <tr>
                    <th>IPI</th>
                </tr>
            </table>
            <table border="1" style="width:100%">
                <tr>
                    <td style="width:10%">CST</td>
                    <td><?php echo $CST; ?></td>
                </tr>
            </table>
            <?php

            /*echo nl2br(
            '######## IPI #######'. "\n".
            $cEnq . "\n".
            $CST . "\n".
            $vBC . "\n".
            $pIPI . "\n".
            $vIPI . "\n"
            );*/

            $PIS = $xml->getElementsByTagName("PIS");
            foreach ($PIS as $PIS) {
                $CST = $PIS->getElementsByTagName("CST");
                $CST = $CST->item(0)->nodeValue;
                $vBC = $PIS->getElementsByTagName("vBC");
                if ($vBC->length > 0) {
                    $vBC = $vBC->item(0)->nodeValue;
                } else {
                    $vBC = '';
                }
                $pPIS = $PIS->getElementsByTagName("pPIS");
                if ($pPIS->length > 0) {
                    $pPIS = $pPIS->item(0)->nodeValue;
                } else {
                    $pPIS = '';
                }
                $vPIS = $PIS->getElementsByTagName("vPIS");
                if ($vPIS->length > 0) {
                    $vPIS = $vPIS->item(0)->nodeValue;
                } else {
                    $vPIS = '';
                }
            }

            ?>
            <table>
                <tr>
                    <th>PIS</th>
                </tr>
            </table>
            <table border="1" style="width:100%">
                <tr>
                    <td style="width:10%">CST</td>
                    <td><?php echo $CST; ?></td>
                </tr>
                <tr>
                    <td style="width:10%">vBC</td>
                    <td><?php echo $vBC; ?></td>
                </tr>
                <tr>
                    <td style="width:10%">pPIS</td>
                    <td><?php echo $pPIS; ?></td>
                </tr>
                <tr>
                    <td style="width:10%">vPIS</td>
                    <td><?php echo $vPIS; ?></td>
                </tr>
            </table>
            <?php

            /*echo nl2br(
            '######## PIS #######'. "\n".
            $CST . "\n".
            $vBC . "\n".
            $pPIS . "\n".
            $vPIS . "\n"
            );*/

            $COFINS = $xml->getElementsByTagName("COFINS");
            foreach ($COFINS as $COFINS) {
                $CST = $COFINS->getElementsByTagName("CST");
                $CST = $CST->item(0)->nodeValue;
                $vBC = $COFINS->getElementsByTagName("vBC");
                if ($vBC->length > 0) {
                    $vBC = $vBC->item(0)->nodeValue;
                } else {
                    $vBC = '';
                }
                $pCOFINS = $COFINS->getElementsByTagName("pCOFINS");
                if ($pCOFINS->length > 0) {
                    $pCOFINS = $pCOFINS->item(0)->nodeValue;
                } else {
                    $pCOFINS = '';
                }
                $vCOFINS = $COFINS->getElementsByTagName("vCOFINS");
                if ($vCOFINS->length > 0) {
                    $vCOFINS = $vCOFINS->item(0)->nodeValue;
                } else {
                    $vCOFINS = '';
                }
            }

            ?>
            <table>
                <tr>
                    <th>COFINS</th>
                </tr>
            </table>
            <table border="1" style="width:100%">
                <tr>
                    <td style="width:10%">CST</td>
                    <td><?php echo $CST; ?></td>
                </tr>
                <tr>
                    <td style="width:10%">vBC</td>
                    <td><?php echo $vBC; ?></td>
                </tr>
                <tr>
                    <td style="width:10%">pCOFINS</td>
                    <td><?php echo $pCOFINS; ?></td>
                </tr>
                <tr>
                    <td style="width:10%">vCOFINS</td>
                    <td><?php echo $vCOFINS; ?></td>
                </tr>
            </table>
            </table>
        <?php

            /*echo nl2br(
            '######## COFINS #######'. "\n".
            $CST . "\n".
            $vBC . "\n".
            $pCOFINS . "\n".
            $vCOFINS . "\n"
            );*/
        }

        echo nl2br("\n");

        $total = $xml->getElementsByTagName("total");
        foreach ($total as $total) {
            $vBC = $total->getElementsByTagName("vBC");
            $vBC = $vBC->item(0)->nodeValue;
            $vICMS = $total->getElementsByTagName("vICMS");
            $vICMS = $vICMS->item(0)->nodeValue;
            $vICMSDeson = $total->getElementsByTagName("vICMSDeson");
            $vICMSDeson = $vICMSDeson->item(0)->nodeValue;
            $vFCP = $total->getElementsByTagName("vFCP");
            $vFCP = $vFCP->item(0)->nodeValue;
            $vBCST = $total->getElementsByTagName("vBCST");
            $vBCST = $vBCST->item(0)->nodeValue;
            $vST = $total->getElementsByTagName("vST");
            $vST = $vST->item(0)->nodeValue;
            $vFCPST = $total->getElementsByTagName("vFCPST");
            $vFCPST = $vFCPST->item(0)->nodeValue;
            $vFCPSTRet = $total->getElementsByTagName("vFCPSTRet");
            $vFCPSTRet = $vFCPSTRet->item(0)->nodeValue;
            $vProd = $total->getElementsByTagName("vProd");
            $vProd = $vProd->item(0)->nodeValue;
            $vFrete = $total->getElementsByTagName("vFrete");
            $vFrete = $vFrete->item(0)->nodeValue;
            $vSeg = $total->getElementsByTagName("vSeg");
            $vSeg = $vSeg->item(0)->nodeValue;
            $vDesc = $total->getElementsByTagName("vDesc");
            $vDesc = $vDesc->item(0)->nodeValue;
            $vII = $total->getElementsByTagName("vII");
            $vII = $vII->item(0)->nodeValue;
            $vIPI = $total->getElementsByTagName("vIPI");
            $vIPI = $vIPI->item(0)->nodeValue;
            $vIPIDevol = $total->getElementsByTagName("vIPIDevol");
            $vIPIDevol = $vIPIDevol->item(0)->nodeValue;
            $vPIS = $total->getElementsByTagName("vPIS");
            $vPIS = $vPIS->item(0)->nodeValue;
            $vCOFINS = $total->getElementsByTagName("vCOFINS");
            $vCOFINS = $vCOFINS->item(0)->nodeValue;
            $vOutro = $total->getElementsByTagName("vOutro");
            $vOutro = $vOutro->item(0)->nodeValue;
            $vNF = $total->getElementsByTagName("vNF");
            $vNF = $vNF->item(0)->nodeValue;
            $vTotTrib = $total->getElementsByTagName("vTotTrib");
            if ($vTotTrib->length > 0) {
                $vTotTrib = $vTotTrib->item(0)->nodeValue;
            } else {
                $vTotTrib = '';
            }

        ?>
            <table>
                <tr>
                    <th>TOTAIS DA NFe</th>
                </tr>
            </table>
            <table border="1" style="width:100%">
                <tr>
                    <td style="width:10%">vBC</td>
                    <td><?php echo $vBC; ?></td>
                </tr>
                <tr>
                    <td style="width:10%">vICMS</td>
                    <td><?php echo $vICMS; ?></td>
                </tr>
                <tr>
                    <td style="width:10%">vICMSDeson</td>
                    <td><?php echo $vICMSDeson; ?></td>
                </tr>
                <tr>
                    <td style="width:10%">vFCP</td>
                    <td><?php echo $vFCP; ?></td>
                </tr>
                <tr>
                    <td style="width:10%">vBCST</td>
                    <td><?php echo $vBCST; ?></td>
                </tr>
                <tr>
                    <td style="width:10%">vST</td>
                    <td><?php echo $vST; ?></td>
                </tr>
                <tr>
                    <td style="width:10%">vFCPST</td>
                    <td><?php echo $vFCPST; ?></td>
                </tr>
                <tr>
                    <td style="width:10%">vFCPSTRet</td>
                    <td><?php echo $vFCPSTRet; ?></td>
                </tr>
                <tr>
                    <td style="width:10%">vProd</td>
                    <td><?php echo $vProd; ?></td>
                </tr>
                <tr>
                    <td style="width:10%">vFrete</td>
                    <td><?php echo $vFrete; ?></td>
                </tr>
                <tr>
                    <td style="width:10%">vSeg</td>
                    <td><?php echo $vSeg; ?></td>
                </tr>
                <tr>
                    <td style="width:10%">vDesc</td>
                    <td><?php echo $vDesc; ?></td>
                </tr>
                <tr>
                    <td style="width:10%">vII</td>
                    <td><?php echo $vII; ?></td>
                </tr>
                <tr>
                    <td style="width:10%">vIPI</td>
                    <td><?php echo $vIPI; ?></td>
                </tr>
                <tr>
                    <td style="width:10%">vIPIDevol</td>
                    <td><?php echo $vIPIDevol; ?></td>
                </tr>
                <tr>
                    <td style="width:10%">vPIS</td>
                    <td><?php echo $vPIS; ?></td>
                </tr>
                <tr>
                    <td style="width:10%">vCOFINS</td>
                    <td><?php echo $vCOFINS; ?></td>
                </tr>
                <tr>
                    <td style="width:10%">vOutro</td>
                    <td><?php echo $vOutro; ?></td>
                </tr>
                <tr>
                    <td style="width:10%">vNF</td>
                    <td><?php echo $vNF; ?></td>
                </tr>
                <tr>
                    <td style="width:10%">vTotTrib</td>
                    <td><?php echo $vTotTrib; ?></td>
                </tr>
            </table>
        <?php

            /*echo nl2br(
            "\n".'######## TOTAIS DA NFe #######'. "\n".
            $vBC . "\n".
            $vICMS . "\n".
            $vICMSDeson . "\n".
            $vFCP . "\n".
            $vBCST . "\n".
            $vST . "\n".
            $vFCPST . "\n".
            $vFCPSTRet . "\n".
            $vProd . "\n".
            $vFrete . "\n".
            $vSeg . "\n".
            $vDesc . "\n".
            $vII . "\n".
            $vIPI . "\n".
            $vIPIDevol . "\n".
            $vPIS . "\n".
            $vCOFINS . "\n".
            $vOutro . "\n".
            $vNF . "\n".
            $vTotTrib . "\n"
            );*/
        }

        echo nl2br("\n");

        $transp = $xml->getElementsByTagName("transp");
        foreach ($transp as $transp) {
            $modFrete = $transp->getElementsByTagName("modFrete");
            $modFrete = $modFrete->item(0)->nodeValue;
            $CNPJ = $transp->getElementsByTagName("CNPJ");
            if ($CNPJ->length > 0) {
                $CNPJ = $CNPJ->item(0)->nodeValue;
            } else {
                $CNPJ = '';
            }
            $xNome = $transp->getElementsByTagName("xNome");
            if ($xNome->length > 0) {
                $xNome = $xNome->item(0)->nodeValue;
            } else {
                $xNome = '';
            }
            $IE = $transp->getElementsByTagName("IE");
            if ($IE->length > 0) {
                $IE = $IE->item(0)->nodeValue;
            } else {
                $IE = '';
            }
            $xEnder = $transp->getElementsByTagName("xEnder");
            if ($xEnder->length > 0) {
                $xEnder = $xEnder->item(0)->nodeValue;
            } else {
                $xEnder = '';
            }
            $xMun = $transp->getElementsByTagName("xMun");
            if ($xMun->length > 0) {
                $xMun = $xMun->item(0)->nodeValue;
            } else {
                $xMun = '';
            }
            $UF = $transp->getElementsByTagName("UF");
            if ($UF->length > 0) {
                $UF = $UF->item(0)->nodeValue;
            } else {
                $UF = '';
            }
            $qVol = $transp->getElementsByTagName("qVol");
            if ($qVol->length > 0) {
                $qVol = $qVol->item(0)->nodeValue;
            } else {
                $qVol = '';
            }
            $nVol = $transp->getElementsByTagName("nVol");
            if ($nVol->length > 0) {
                $nVol = $nVol->item(0)->nodeValue;
            } else {
                $nVol = '';
            }

        ?>
            <table>
                <tr>
                    <th>INFORMAÃ‡Ã•ES DE TRANSPORTE</th>
                </tr>
            </table>
            <table border="1" style="width:100%">
                <tr>
                    <td style="width:10%">modFrete</td>
                    <td><?php echo $modFrete; ?></td>
                </tr>
                <tr>
                    <td style="width:10%">CNPJ</td>
                    <td><?php echo $CNPJ; ?></td>
                </tr>
                <tr>
                    <td style="width:10%">xNome</td>
                    <td><?php echo $xNome; ?></td>
                </tr>
                <tr>
                    <td style="width:10%">IE</td>
                    <td><?php echo $IE; ?></td>
                </tr>
                <tr>
                    <td style="width:10%">xEnder</td>
                    <td><?php echo $xEnder; ?></td>
                </tr>
                <tr>
                    <td style="width:10%">xMun</td>
                    <td><?php echo $xMun; ?></td>
                </tr>
                <tr>
                    <td style="width:10%">UF</td>
                    <td><?php echo $UF; ?></td>
                </tr>
                <tr>
                    <td style="width:10%">qVol</td>
                    <td><?php echo $qVol; ?></td>
                </tr>
            </table>
        <?php

            /*echo nl2br(
            "\n".'######## INFORMAÃ‡Ã•ES DE TRANSPORTE #######'. "\n".
            $modFrete . "\n".
            $CNPJ . "\n".
            $xNome . "\n".
            $IE . "\n".
            $xEnder . "\n".
            $xMun . "\n".
            $UF . "\n".
            $qVol . "\n".
            $esp . "\n".
            $nVol . "\n".
            $pesoL . "\n".
            $pesoB . "\n"
            );*/
        }

        echo nl2br("\n");

        //echo nl2br(
        //"\n".'######## INFORMAÃ‡Ã•ES DE PAGAMENTO #######'. "\n");

        $pag = $xml->getElementsByTagName("pag");
        foreach ($pag as $detPag) {
            $tPag = $detPag->getElementsByTagName("tPag");
            $tPag = $tPag->item(0)->nodeValue;
            $vPag = $detPag->getElementsByTagName("vPag");
            $vPag = $vPag->item(0)->nodeValue;
            $card = $detPag->getElementsByTagName("card");
            if ($card->length > 0) {
                $card = $card->item(0)->nodeValue;
            } else {
                $card = '';
            }
            //$tpIntegra = $detPag->getElementsByTagName( "tpIntegra" );
            //$tpIntegra = $tpIntegra->item(0)->nodeValue;

        ?>
            <table>
                <tr>
                    <th>INFORMAÃ‡Ã•ES DE PAGAMENTO</th>
                </tr>
            </table>
            <table border="1" style="width:100%">
                <tr>
                    <td style="width:10%">tPag</td>
                    <td><?php echo $tPag; ?></td>
                </tr>
                <tr>
                    <td style="width:10%">vPag</td>
                    <td><?php echo $vPag; ?></td>
                </tr>
                <tr>
                    <td style="width:10%">card</td>
                    <td><?php echo $card; ?></td>
                </tr>
            </table>
        <?php

            /*echo nl2br(
            $tPag . "\n".
            $vPag . "\n".
            $card . "\n"
            //$tpIntegra . "\n"
            );*/
        }

        echo nl2br("\n");

        $infAdic = $xml->getElementsByTagName("infAdic");
        foreach ($infAdic as $infAdic) {
            $infAdFisco = $infAdic->getElementsByTagName("infAdFisco");
            if ($infAdFisco->length > 0) {
                $infAdFisco = $infAdFisco->item(0)->nodeValue;
            } else {
                $infAdFisco = '';
            }
            $infCpl = $infAdic->getElementsByTagName("infCpl");
            $infCpl = $infCpl->item(0)->nodeValue;

        ?>
            <table>
                <tr>
                    <th>INFORMAÃ‡Ã•ES ADICIONAIS</th>
                </tr>
            </table>
            <table border="1" style="width:100%">
                <tr>
                    <td style="width:10%">infAdFisco</td>
                    <td><?php echo $infAdFisco; ?></td>
                </tr>
                <tr>
                    <td style="width:10%">infCpl</td>
                    <td><?php echo $infCpl; ?></td>
                </tr>
            </table>
        <?php

            /*echo nl2br(
            "\n".'######## INFORMAÃ‡Ã•ES ADICIONAIS #######'. "\n".
            $infAdFisco . "\n".
            $infCpl . "\n"
            );*/
        }

        echo nl2br("\n");

        $infRespTec = $xml->getElementsByTagName("infRespTec");
        foreach ($infRespTec as $infRespTec) {
            $CNPJ = $infRespTec->getElementsByTagName("CNPJ");
            $CNPJ = $CNPJ->item(0)->nodeValue;
            $xContato = $infRespTec->getElementsByTagName("xContato");
            $xContato = $xContato->item(0)->nodeValue;
            $email = $infRespTec->getElementsByTagName("email");
            $email = $email->item(0)->nodeValue;
            $fone = $infRespTec->getElementsByTagName("fone");
            $fone = $fone->item(0)->nodeValue;

        ?>
            <table>
                <tr>
                    <th>INFORMAÃ‡Ã•ES DO RESPONSÃVEL TÃ‰CNICO</th>
                </tr>
            </table>
            <table border="1" style="width:100%">
                <tr>
                    <td style="width:10%">CNPJ</td>
                    <td><?php echo $CNPJ; ?></td>
                </tr>
                <tr>
                    <td style="width:10%">xContato</td>
                    <td><?php echo $xContato; ?></td>
                </tr>
                <tr>
                    <td style="width:10%">email</td>
                    <td><?php echo $email; ?></td>
                </tr>
                <tr>
                    <td style="width:10%">fone</td>
                    <td><?php echo $fone; ?></td>
                </tr>
            </table>
        <?php

            /*echo nl2br(
            "\n".'######## INFORMAÃ‡Ã•ES DO RESPONSÃVEL TÃ‰CNICO #######'. "\n".
            $CNPJ . "\n".
            $xContato . "\n".
            $email . "\n".
            $fone . "\n"
            );*/
        }

        $infProt = $xml->getElementsByTagName("infProt");
        foreach ($infProt as $infProt) {
            $tpAmb = $infProt->getElementsByTagName("tpAmb");
            $tpAmb = $tpAmb->item(0)->nodeValue;
            $verAplic = $infProt->getElementsByTagName("verAplic");
            $verAplic = $verAplic->item(0)->nodeValue;
            $chNFe = $infProt->getElementsByTagName("chNFe");
            $chNFe = $chNFe->item(0)->nodeValue;
            $dhRecbto = $infProt->getElementsByTagName("dhRecbto");
            $dhRecbto = $dhRecbto->item(0)->nodeValue;
            $nProt = $infProt->getElementsByTagName("nProt");
            $nProt = $nProt->item(0)->nodeValue;
            $digVal = $infProt->getElementsByTagName("digVal");
            $digVal = $digVal->item(0)->nodeValue;
            $cStat = $infProt->getElementsByTagName("cStat");
            $cStat = $cStat->item(0)->nodeValue;
            $xMotivo = $infProt->getElementsByTagName("xMotivo");
            $xMotivo = $xMotivo->item(0)->nodeValue;
            //Autorizado o uso da NF-e
        ?>
            <table>
                <tr>
                    <th>INFORMAÃ‡Ã•ES TÃ‰CNICAS DA NFe</th>
                </tr>
            </table>
            <table border="1" style="width:100%">
                <tr>
                    <td style="width:10%">tpAmb</td>
                    <td><?php echo $tpAmb; ?></td>
                </tr>
                <tr>
                    <td style="width:10%">verAplic</td>
                    <td><?php echo $verAplic; ?></td>
                </tr>
                <tr>
                    <td style="width:10%">chNFe</td>
                    <td><?php echo $chNFe; ?></td>
                </tr>
                <tr>
                    <td style="width:10%" style="width:10%">dhRecbto</td>
                    <td><?php echo $dhRecbto; ?></td>
                </tr>
                <tr>
                    <td style="width:10%">nProt</td>
                    <td><?php echo $nProt; ?></td>
                </tr>
                <tr>
                    <td style="width:10%">digVal</td>
                    <td><?php echo $digVal; ?></td>
                </tr>
                <tr>
                    <td style="width:10%">cStat</td>
                    <td><?php echo $cStat; ?></td>
                </tr>
                <tr>
                    <td style="width:10%">xMotivo</td>
                    <td><?php echo $xMotivo; ?></td>
                </tr>
            </table>
<?php

            /*echo nl2br(
            "\n".'######## INFORMAÃ‡Ã•ES TÃ‰CNICAS DA NFe #######'. "\n".
            $tpAmb . "\n".
            $verAplic . "\n".
            $chNFe . "\n".
            $dhRecbto . "\n".
            $nProt . "\n".
            $digVal . "\n".
            $cStat . "\n".
            $xMotivo . "\n"
            );*/
        }

        // renomeia o arquivo para que nome fique = chave acesso

        return $chNFe;
    } // exbeXml




} // importXmlController
