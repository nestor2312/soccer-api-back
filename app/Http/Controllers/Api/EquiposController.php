<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Equipo;
use App\Models\Grupos;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EquiposController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

//   */
 public function asignarAGrupo(Request $request, $grupoId)
{
    $grupo = Grupos::findOrFail($grupoId);

    $request->validate([
        'equipo_ids'   => 'nullable|array', // 'nullable' permite enviar un array vacío [] si desmarcan todos
        'equipo_ids.*' => 'exists:equipos,id'
    ]);

    // Usamos sync() para sincronizar exactos los IDs recibidos:
    // Agrega los nuevos, mantiene los seleccionados y ELIMINA los que se desmarcaron.
    $grupo->equipos()->sync($request->input('equipo_ids', []));

    return response()->json([
        'message' => 'Equipos actualizados correctamente en el grupo ' . $grupo->nombre
    ], 200);
}

    /**
     * Remover un equipo de un grupo específico
     */
    public function removerDeGrupo($grupoId, $equipoId)
    {
        $grupo = Grupos::findOrFail($grupoId);
        
        // Elimina el registro de la tabla pivote
        $grupo->equipos()->detach($equipoId);

        return response()->json(['message' => 'Equipo removido del grupo correctamente']);
    }

    /**
     * Obtener todos los equipos pertenecientes a un grupo
     */
    public function equiposXgrupo($grupoId)
    {
        $grupo = Grupos::with('equipos')->findOrFail($grupoId);
        
        return response()->json($grupo->equipos);
    }


   
    public function index()
    {
      
        // $equipos = Equipo::all();
        // return $equipos ;

                $equipos = Equipo::with('grupos.subcategoria.categoria.torneo')->orderBy('id', 'desc')->paginate(10); 
      
        return $equipos;


    }

    public function store(Request $request)
    {
       
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            // 'grupo_id' => 'required|integer|exists:grupos,id',
            'archivo' => 'nullable|file|image|max:2048', 
             'color_hover' => 'nullable|string',
        ]);
    
        // Creando el nuevo equipo
        $equipo = new Equipo();
        $equipo->nombre = $request->nombre;  // Usando el método $request->nombre
        // $equipo->grupo_id = $request->grupo_id; // Usando el método $request->grupo_id
         $equipo->color_hover = $request->color_hover;
        
        // Verificando si se subió un archivo
        if ($request->hasFile('archivo')) {
            $file = $request->file('archivo');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('public/uploads', $filename); // Guardando el archivo
            $equipo->archivo = $filename; // Guardando el nombre del archivo en la base de datos
        }
    
        // Guardando el equipo en la base de datos
        $equipo->save();
    
        // Respondiendo con el equipo recién creado
        return response()->json($equipo, 201);
    }
    
    
    
    

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Validación de los datos de entrada
        $request->validate([
            // 'grupo_id' => 'required|exists:grupos,id',
            'nombre' => 'required|string|max:255',
             'color_hover' => 'nullable|string',
            'archivo' => [
                'nullable',
                'string',
                'regex:/^data:image\/(jpeg|png|jpg|gif|webp);base64,/',
            ],
        ]);
        // Buscar el equipo por su ID
        $equipo = Equipo::findOrFail($id);
        // Actualizar los datos del equipo
        $equipo->nombre = $request->nombre;
        // $equipo->grupo_id = $request->grupo_id;
          $equipo->color_hover = $request->color_hover;
        // Verificar si se ha recibido un archivo en base64
        if ($request->has('archivo')) {
            // Eliminar el archivo anterior si existe
            if ($equipo->archivo && Storage::exists('public/uploads/' . $equipo->archivo)) {
                Storage::delete('public/uploads/' . $equipo->archivo); // Eliminar el archivo viejo
            }
            // Extraer tipo de imagen (png, jpeg, jpg)
            if (preg_match('/^data:image\/(\w+);base64,/', $request->archivo, $matches)) {
                $imageType = $matches[1]; // Obtenemos el tipo de imagen (png, jpeg, etc.)
                // Validar el tipo de imagen
                if (!in_array($imageType, ['jpeg', 'png', 'jpg'])) {
                    return response()->json(['error' => 'Tipo de imagen no permitido'], 400);
                }
                $filename = Str::random(20) . '.' . $imageType; // Nombre único con extensión
                // Eliminar el prefijo data:image/...;base64,
                $base64Image = preg_replace('/^data:image\/\w+;base64,/', '', $request->archivo);
                // Decodificar el archivo base64
                $imageData = base64_decode($base64Image);
                // Verificar que la imagen fue decodificada correctamente
                if ($imageData === false) {
                    return response()->json(['error' => 'Error al decodificar la imagen'], 400);
                }
                // Guardar el archivo en el directorio de almacenamiento
                Storage::put('public/uploads/' . $filename, $imageData);
                // Guardar el nombre del archivo en la base de datos
                $equipo->archivo = $filename;
            } else {
                return response()->json(['error' => 'Archivo base64 no válido'], 400);
            }
        }
        // Guardar los cambios en la base de datos
        $equipo->save();
    
        // Responder con el equipo actualizado
        return response()->json($equipo, 200);
    }
    
    
    

    

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $equipo = Equipo::findOrFail($id);  
        $equipo->delete();
        return response()->json(['message' => 'equipo eliminado correctamente']);
    }
}
