<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\Posts;
use Twilio\Rest\Client;
use App\Models\Contactos;
use App\Models\Categorias;
use App\Models\Comentarios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class PostsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $blogs = Posts::select('id', 'titulo', 'imagen', 'resumen', 'categoria_id', 'fecha_publicacion')
                        ->with('categoria')
                        ->orderBy('id', 'DESC')
                        ->paginate(10);

        foreach($blogs as $item){
            $item->imagen = $item->getImagenUrl();
            $item->cant_comentarios = Comentarios::where('post_id', $item->id)->count();
        }

        $paraSlider = Posts::select('posts.id', 'posts.titulo', 'posts.imagen',
                                    'categoria_id', 'categorias.nombre', 'posts.fecha_publicacion',
                                    DB::raw('COUNT(comentarios.id) as cant_comentarios')
                                )
                            ->join('comentarios', 'posts.id', 'comentarios.post_id')
                            ->join('categorias', 'posts.categoria_id', 'categorias.id')
                            ->groupBy('posts.id')
                            ->orderBy('cant_comentarios', 'DESC')
                            ->take(3)
                            ->get();

        foreach($paraSlider as $slide){
            $slide->imagen = $slide->getImagenUrl();
        }

        return response()->json([
            'mensaje' => 'Datos cargados correctamente.',
            'datos' => $blogs,
            'paraSlider' => $paraSlider
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function filtrado(Request $request)
    {
        $busqueda = $request->busqueda;
        // quitar %20 y reemplazarlo por espacio
        $busqueda = str_replace('%20', ' ', $busqueda);

        $blogs = Posts::select('id', 'titulo', 'imagen', 'resumen', 'categoria_id', 'fecha_publicacion')
                        ->with('categoria')
                        ->where('tags', 'LIKE', '%'.$busqueda.'%')
                        ->orWhereHas('categoria', function($query) use ($busqueda){
                            $query->where('id', 'LIKE', '%'.$busqueda.'%')
                                    ->orWhere('nombre', 'LIKE', '%'.$busqueda.'%');
                        })
                        ->orderBy('id', 'DESC')
                        ->paginate(10);

        foreach($blogs as $item){
            $item->imagen = $item->getImagenUrl();
            $item->cant_comentarios = Comentarios::where('post_id', $item->id)->count();
        }
        return response()->json([
            'mensaje' => 'Datos cargados correctamente.',
            'datos' => $blogs
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $post = Posts::with('usuario', 'categoria', 'comentarios', 'comentarios.usuario')->find($id);
        if($post){
            $post->imagen = $post->getImagenUrl();
            $post->tags = json_decode($post->tags);
            $post->fecha_publicacion = Carbon::parse($post->fecha_publicacion)->diffForHumans(now());
            $post->cant_comentarios = $post->comentarios->count();
        }

        return response()->json([
            'mensaje' => 'Post encontrado.',
            'datos' => $post,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function siguiente($id, $antsig)
    {
        $actual = Posts::find($id);
        if($antsig == 'anterior'){
            $post = Posts::where('categoria_id', $actual->categoria_id)
                        ->where('id', '<', $actual->id)
                        ->orderBy('id', 'DESC')
                        ->first();
        } else {
            $post = Posts::where('categoria_id', $actual->categoria_id)
                        ->where('id', '>', $actual->id)
                        ->orderBy('id', 'ASC')
                        ->first();
        }
        return response()->json([
            'mensaje' => 'Post encontrado.',
            'postId' => ($post) ? $post->id : $actual->id,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function categorias()
    {
        $categorias = Categorias::select('id', 'imagen', 'nombre')->where('estado', true)->get();
        foreach($categorias as $cat){
            $cat->imagen = $cat->getImagenUrl();
            $cat->cant_posts = Posts::where('categoria_id', $cat->id)->count();
        }

        return response()->json([
            'mensaje' => 'Datos cargados.',
            'datos' => $categorias,
        ]);
    }

    public function comentario(Request $request){
        $this->validate($request, [
            'post_id' => 'required|exists:posts,id',
            'comentario' => 'required|string|min:10|max:200'
        ]);

        $comment = new Comentarios();
        $comment->post_id = $request->post_id;
        $comment->comentario = $request->comentario;
        $comment->estado = true;
        $comment->fecha = now();
        $comment->usuario_id = auth()->user()->id;
        if ($comment->save()) {
            return response()->json([
                'mensaje' => 'Registro agregado correctamente!',
                'datos' => $comment,
            ]);
        } else {
            return response()->json([
                'mensaje' => 'El registro no fué agregado!'
            ]);
        }
    }

    public function contacto(Request $request){
        $this->validate($request, [
            'nombre' => 'required|string|min:2|max:200',
            'correo' => 'required|email',
            'asunto' => 'required|string|min:5|max:200',
            'mensaje' => 'required|string|min:10|max:500',
            'telefono' => 'required|numeric|digits_between:6,8'
        ]);

        $contacto = new Contactos();
        $contacto->nombre = $request->nombre;
        $contacto->correo = $request->correo;
        $contacto->asunto = $request->asunto;
        $contacto->mensaje = $request->mensaje;
        $contacto->telefono = $request->telefono;
        if ($contacto->save()) {

            // verificacion de telefono o celular

            if(strlen($contacto->telefono) == 8){
                $twiliosid = env('TWILIO_SID');
                $twiliotoken = env('TWILIO_TOKEN');
                $twiliodesde = env('TWILIO_FROM');

                $clienteTwilio = new Client($twiliosid, $twiliotoken);

                $numeroCliente = '+591'.$contacto->telefono;
                $mensajeTwilio = 'Gracias por ponerse en contacto, le reposnderemos a la brevedad posible.';

                $clienteTwilio->messages->create(
                    $numeroCliente, [
                        'from' => $twiliodesde,
                        'body' => $mensajeTwilio
                    ]
                );
            }

            return response()->json([
                'mensaje' => 'Registro agregado correctamente!',
                'datos' => $contacto,
            ]);
        } else {
            return response()->json([
                'mensaje' => 'El registro no fué agregado!'
            ]);
        }
    }


    // ENVIAR EL SMS
    public function enviarSMS($numero){
        $sid = env('TWILIO_SID');
        $token = env('TWILIO_TOKEN');
        $desde = env('TWILIO_FROM');

        $a = '+591'.$numero;
        $mensaje = "Hola, gracias por contactarte con nosotros. Te responderemos a la brevedad posible.";

        $clienteTwilio = new Client($sid, $token);
        $mensajeEnviado = $clienteTwilio->messages->create(
            $a, [
                'from' => $desde,
                'body' => $mensaje
            ]
        );

        return response()->json([
            'mensaje' => 'Mensaje enviado!',
            'datos' => $mensajeEnviado,
        ]);
    }
}
