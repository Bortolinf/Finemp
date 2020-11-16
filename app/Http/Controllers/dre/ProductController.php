<?php

namespace App\Http\Controllers\Erp;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Product;
use App\Models\Unid;
use App\Models\Ncm;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


class ProductController extends Controller
{

    public function __construct(){
        $this->middleware('auth');
    }

    public function index()
    {
        $products = Product::paginate(5);
        return view('erp.products.index', [
            'products' => $products
        ]);

    }

    public function create()
    {
        $loggedId = Auth::id();
        $user = User::find($loggedId);

        if ($user->can('prod_create')) {
            $unids = Unid::all();
            return view('erp.products.create', [
                'unids' => $unids
                ]);
        }
        else {
            return redirect()->route('products.index');
        }
    }

    public function store(Request $request)
    {
        $data = $request->only([
            'refcode',
            'name',
            'description',
            'un',
            'ncm',
            'price'
        ]);

        $validator = Validator::make($data, [
            'name' => ['required', 'string', 'max:100'],
            'description' => ['required', 'string'],
            'un' => ['required', 'string', 'max:2', 'exists:unids,unid'],
            'ncm' => ['required', 'integer', 'exists:ncms,codncm'],
            'price' => ['numeric']
            ]);

        if ($validator->fails()) {
            return redirect()->route('products.create')
                ->withErrors($validator)
                ->withInput();
        }

        // cadastra o Produto
        $product = new Product;
        $product->refcode = $data['refcode'];
        $product->name = $data['name'];
        $product->description = $data['description'];
        $product->un  = $data['un'];
        $product->ncm = $data['ncm'];
        $product->price = $data['price'];
        $product->save();

        return redirect()->route('products.index');


    } // fim do store



    public function show($id)
    {
    //
    }

    public function edit($id)
    {
        $product = Product::find($id);
        if ($product) {
            $unids = Unid::all();
            $product->ncmDescr = Ncm::where('codncm', $product['ncm'])->first()->description;
            return view('erp.products.edit', [
                'product' => $product,
                'unids' => $unids
            ]);
        }
        return redirect()->route('products.index');
        
    }

    public function update(Request $request, $id)
    {

        $product = Product::find($id);
        if ($product) {
            $data = $request->only([
                'refcode',
                'name',
                'description',
                'un',
                'ncm',
                'price'
            ]);

            $validator = Validator::make($data, [
                'name' => ['required', 'string', 'max:100'],
                'description' => ['required', 'string'],
                'un' => ['required', 'string', 'max:2', 'exists:unids,unid'],
                'ncm' => ['required', 'integer', 'exists:ncms,codncm'],
                'price' => ['numeric']
                ]);
            
 
            if ($validator->fails()) {
                return redirect()->route('products.edit', [
                    'product' => $id
                ])->withErrors($validator)->withInput();
        }

            $product->refcode = $data['refcode'];
            $product->name = $data['name'];
            $product->description = $data['description'];
            $product->un  = $data['un'];
            $product->ncm = $data['ncm'];
            $product->price = $data['price'];
    
            $product->save();
            
        } // if($product)

        return redirect()->route('products.index');

        
    }

    public function destroy($id)
    {
        $product = Product::find($id);
        $product->delete();
        return redirect()->route('products.index');
    }


    public function toPdf()
    {
        $products = Product::all();
     
        return \PDF::loadView('erp.products.report', compact('products'))
                    // Se quiser que fique no formato a4 retrato: ->setPaper('a4', 'landscape')
                    ->download('products-report.pdf');
    }

    public function toExcel()
    {
   //  echo 'vou gerar um excel aqui';
        $produtos = Product::all();
        $htmlString = '<table><tr>';
        $htmlString .= '<td>Id</td>';
        $htmlString .= '<td>Ref.Code</td>';
        $htmlString .= '<td>Descr.Reduzida</td>';
        $htmlString .= '<td>Descrição</td>';
        $htmlString .= '<td>un</td>';
        $htmlString .= '<td>NCM</td>';
        $htmlString .= '<td>Preço</td></tr>';
        foreach ($produtos as $produto) {
            $htmlString .= '<tr><td>'.$produto->id.'</td>';
            $htmlString .= '<td>'.$produto->refcode.'</td>';
            $htmlString .= '<td>'.$produto->name.'</td>';
            $htmlString .= '<td>'.$produto->description.'</td>';
            $htmlString .= '<td>'.$produto->un.'</td>';
            $htmlString .= '<td>'.$produto->ncm.'</td>';
            $htmlString .= '<td>'.$produto->price.'</td></tr>';
        }
        $htmlString .= '</html>';

        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Html();
        $spreadsheet = $reader->loadFromString($htmlString);
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xls');
        $file = 'produtos.xls';
        $writer->save($file);    
    
        if (file_exists($file)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="'.basename($file).'"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file));
            readfile($file);
            exit;
        }
        return redirect()->route('products.index');
        
      
    }




}
