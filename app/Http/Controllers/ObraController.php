<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Obra;
use Illuminate\Validation\Rule;

class ObraController extends Controller
{

    public function index()
    {
        $obras = Obra::all();
        $response = array(
            "status" => 200,
            "menssage" => "Todos los registros de las obras",
            "data" => $obras
        );
        return view('obras.index', ['obras' => $obras]);
    }
    /**
     * Metodo POST para crear un registro
     */
    public function store(Request $request)
    {
        $obra = new Obra();
        $obra->idArtista = $request->idArtista;
        $obra->tecnica = $request->tecnica;
        $obra->nombre = $request->nombre;
        $obra->tamaño = $request->tamaño;
        $obra->precio = $request->precio;
        $obra->disponibilidad = 'disponible';
        $obra->categoria = $request->categoria;
        $obra->imagen = null;
        echo ($obra);
        $obra->save();
        return redirect()->route('obras')->with('success', 'Todo created successfully');
    }

    public function show($id)
    {
        $obra = Obra::find($id);
        if (is_object($obra)) {
            $response = array(
                'status' => 200,
                'menssage' => 'Obra encontrada',
                'Obra' => $obra
            );
        } else {
            $response = array(
                'status' => 404,
                'menssage' => 'Recurso no encontrado'
            );

        }
        return view('obras.show', ['obra' => $obra]);
    }


    public function destroy($id)
    {
        if (isset($id)) {
            var_dump($id);
            $obra = Obra::find($id);
            var_dump($obra);
            if (!$obra) {
                $response = array(
                    'status' => 404,
                    'message' => 'Obra no encontrada'
                );
                return response()->json($response, $response['status']);
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

        return redirect()->route('obras')->with('success', 'Todo deleted successfully');
    }


    //patch
    public function update(Request $request, $id)
    {

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

        if (isset($data_input['tecnica'])) {
            $obra->tecnica = $data_input['tecnica'];
        }
        if (isset($data_input['nombre'])) {
            $obra->nombre = $data_input['nombre'];
        }
        if (isset($data_input['tamaño'])) {
            $obra->tamaño = $data_input['tamaño'];
        }
        if (isset($data_input['precio'])) {
            $obra->precio = $data_input['precio'];
        }
        if (isset($data_input['disponibilidad'])) {
            $obra->disponibilidad = $data_input['disponibilidad'];
        }
        if (isset($data_input['categoria'])) {
            $obra->categoria = $data_input['categoria'];
        }
        if (isset($data_input['imagen'])) {
            $obra->imagen = $data_input['imagen'];
        }

        $obra->save();

        $response = [
            'status' => 201,
            'message' => 'Obra actualizada',
            'Obra' => $obra
        ];
        return redirect()->route('obra')->with('success', 'Todo updated successfully');
    }
}
