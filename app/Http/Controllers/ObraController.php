<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Obra;
use Illuminate\Validation\Rule;

class ObraController extends Controller
{
   

    public function index()
    {
        $data=Obra::all();
        $response=array(
            "status"=>200,
            "menssage"=>"Todos los registros de las peliculas",
            "data"=>$data
        );
        return response()->json($response,200);
    }
    /**
     * Metodo POST para crear un registro
     */
    public function store(Request $request){
        $data_input = $request->input('data',null);
        if($data_input){
            $data = json_decode($data_input,true);
            $data=array_map('trim',$data);

            $tecnica = Obra::getTecnica();

            $rules = [
                'idArtista' => 'required|string|max:40',
                'tecnica' => ['required', Rule::in($tecnica)],
                'nombre' => 'required|string',
                'tamaño' => 'required|max:20',
                'precio' => 'required|decimal:0,4',
                'disponibilidad' => 'required|max:20',
                'categoria' => 'required|max:45',
                'imagen' => 'required|max:45',
            ];
            
            $isValid =\validator($data,$rules);
            if(!$isValid->fails()){
                $obra = new Obra();
                $obra->idArtista=$data['idArtista'];
                $obra->tecnica=$data['tecnica'];
                $obra->nombre=$data['nombre'];
                $obra->tamaño=$data['tamaño'];
                $obra->precio=$data['precio'];
                $obra->disponibilidad=$data['disponibilidad'];
                $obra->categoria=$data['categoria'];
                $obra->imagen=$data['imagen'];
 
                $obra->save();
                $response = array(
                    'status'=>201,
                    'menssage'=>'Obra creada',
                    'Obra'=>$obra
                );
            }else{
                $response = array(
                    'status'=>406,
                    'menssage'=>'Datos invalidos',
                    'errors'=>$isValid->errors()
                );
            }
        }else{
            $response = array(
                'status'=>400,
                'menssage'=>'No se encontro el objeto data'
            );
        }
   
        return response()->json($response,$response['status']);
    }
 
        public function show($id){
            $data=Obra::find($id);
            if(is_object($data)){
                $data=$data->load('imagenes');
                $response=array(
                'status'=>200,
                'menssage'=>'Obra encontrada',
                'Obra'=>$data
                );
            }
            else{
                $response = array(
                    'status'=>404,
                    'menssage'=>'Recurso no encontrado'
                );

            }
            return response()->json($response,$response['status']);
        }


        public function destroy(Request $request, $id){
   
            if (isset($id)) {
                $obra = Obra::find($id);
                if (!$obra) {
                    $response = array(
                        'status' => 404,
                        'message' => 'Obra no encontrada'
                    );
                    return response()->json($response, $response['status']);
                }
        
                $imagenes = $obra->imagenes;
        
                if ($imagenes) {
                    foreach ($imagenes as $imagen) {
                        $filename = $imagen->imagen;
                        \Storage::disk('obras')->delete($filename);
                    }
                }
                
                $delete = Obra::where('id', $id)->delete();
                if ($delete) {
                    $response = array(
                        'status' => 200,
                        'message' => 'Obra eliminada',
                    );
                } else {
                    $response = array(
                        'status' => 400,
                        'message' => 'No se pudo eliminar la obra, compruebe que exista'
                    );
                }
            } else {
                $response = array(
                    'status' => 406,
                    'message' => 'Falta el identificador del recurso a eliminar'
                );
            }
        
            return response()->json($response, $response['status']);
        }
        

        //patch
    public function update(Request $request, $id) {
    
        $obra = Obra::find($id);
        if (!$obra) {
            $response = [
                'status' => 404,
                'message' => 'Obra no encontrada'
            ];
            return response()->json($response, $response['status']);
        }
    
        $data_input = $request->input('data', null);
        $data_input = json_decode($data_input, true);
    
        if (!$data_input) {
            $response = [
                'status' => 400,
                'message' => 'No se encontró el objeto data. No hay datos que modificar'
            ];
            return response()->json($response, $response['status']);
        }
    
        $tecnica = Obra::getTecnica();

        $rules = [
            'idArtista' => 'string|max:40',
            'tecnica' => Rule::in($tecnica),
            'nombre' => 'string',
            'tamaño' => 'max:20',
            'precio' => 'decimal:0,4',
            'disponibilidad' => 'max:20',
            'categoria' => 'max:45',
            'imagen' => 'max:45',
        ];

        $validator = \validator($data_input, $rules);
    
        if ($validator->fails()) {
            $response = [
                'status' => 406,
                'message' => 'Datos inválidos',
                'error' => $validator->errors()
            ];
            return response()->json($response, $response['status']);
        }
    
        if(isset($data_input['tecnica'])) { $obra->tecnica = $data_input['tecnica']; }
        if(isset($data_input['nombre'])) { $obra->nombre = $data_input['nombre']; }
        if(isset($data_input['tamaño'])) { $obra->tamaño = $data_input['tamaño']; }
        if(isset($data_input['precio'])) { $obra->precio = $data_input['precio']; }
        if(isset($data_input['disponibilidad'])) { $obra->disponibilidad = $data_input['disponibilidad']; }
        if(isset($data_input['categoria'])) { $obra->categoria = $data_input['categoria']; }
        if(isset($data_input['imagen'])) { $obra->imagen = $data_input['imagen']; }

        $obra->save();
    
        $response = [
            'status' => 201,
            'message' => 'Obra actualizada',
            'Obra' => $obra
        ];
        return response()->json($response, $response['status']);
    }
}
