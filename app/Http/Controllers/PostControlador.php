<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
use Illuminate\Support\Facades\Storage;

class PostControlador extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::all();
        return view('index', compact(['posts']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Vai salvar dentro de storage/public/imagens
        // Digo que vai salvar na public, visto que em config/filesystem
        // Est configurado para salvar no local e ele aponta para o app e não para o public
        // Essa função storage vai retornar um path e mais um nome, onde o nome é aleatório.
        $path = $request->file('arquivo')->store('imagens', 'public');
        $post = new Post();
        $post->email = $request->email;
        $post->mensagem = $request->mensagem;
        $post->arquivo = $path;
        $post->save();
        return redirect('/');
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = Post::find($id);
        if (isset($post)) {
            // Recupero o nome do arquivo antes de apagar.
            $arquivo = $post->arquivo;

            // Digo qual é o driver(public) e uso o delete que vai apagar o arquivo no sistema de arquivos.
            Storage::disk('public')->delete($arquivo);

            // Aqui apaga da base de dados.
            $post->delete();
        }

        return redirect('/');
    }

    public function download($id)
    {
        $post = Post::find($id);
        if (isset($post)) {
            // Pega o path absoluto de onde tá o arquivo.
            $path = Storage::disk('public')->getDriver()->getAdapter()->applyPathPrefix($post->arquivo);
            // Para fazer o downlod é so usar o response usando o método download passando o path do arquivo.
            return response()->download($path);

        }
        return redirect('/');
    }
}
