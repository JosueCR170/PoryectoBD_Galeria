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
        return view('obras.show', ['obra' => $obra]);
    }


    public function destroy($id)
    {
        if (isset($id)) {

            $delete = Obra::where('id', $id)->delete();
            if ($delete) {
                return redirect()->route('obras')->with('success', 'Obra eliminada correctamente');
            } else {
            return redirect()->route('obras')->with('Error', 'Obra no eliminada');
            }
        }
        return redirect()->route('obras')->with('Error', 'Obra no encontrada');
    }


    //patch
    public function update(Request $request, $id)
    {
        if (isset($id)) {
            $obra = Obra::find($id);
            if (isset($request->tecnica)) {
                $obra->tecnica = $request->tecnica;
            }
            if (isset($request->nombre)) {
                $obra->nombre = $request->nombre;
            }
            if (isset($request->tamaño)) {
                $obra->tamaño = $request->tamaño;
            }
            if (isset($request->precio)) {
                $obra->precio = $request->precio;
            }
            if (isset($request->disponibilidad)) {
                $obra->disponibilidad = $request->disponibilidad;
            }
            if (isset($request->categoria)) {
                $obra->categoria = $request->categoria;
            }
            if (isset($request->imagen)) {
                $obra->imagen = $request->imagen;
            }
            $obra->save();
            return redirect()->route('obras')->with('success', 'Todo updated successfully');
        }
    }
}
