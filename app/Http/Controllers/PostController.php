<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Validator;
use Session;
use Exception;

use App;
use App\User;
use App\Post;
use App\Category;
use App\Tag;
use App\Http\Requests\PostRequest;

class PostController extends Controller
{

    public function filterExistingTagIds($tags)
    {
        $tagsOk = [];
        if(isset($tags) && is_array($tags) && count($tags)>0){
            foreach ($tags as $key => $tagId) {
                if(Tag::find($tagId)){
                    $tagsOk[] = $tagId;
                }
            }
        }
        return $tagsOk;
    }

    public function minValidationRequired(PostRequest $request)
    {
        $validator = Validator::make(
            $request->all(),
            $request->rules(),
            $request->messages()
            );

        if($validator->valid()){
            if(!User::find($request->user_id)){
                throw new Exception("El usuario no existe", 1);
            }

            if(!Category::find($request->category_id)){
                throw new Exception("La categoria no existe",1);
            }

            $tags = $request->tags;
            $tagsOk = $this->filterExistingTagIds($request->tags);

            if(count($tagsOk)<1){
                throw new Exception("Todas las etiquetas son erroneas",1);
            }
        }
        

    }

    public function add(PostRequest $request)
    {
        try {
            $this->minValidationRequired($request);
                $post = new Post();
                $post->title = $request->title;
                $post->body = $request->body;
                $post->user_id = $request->user_id;
                $post->category_id= $request->category_id;
                $post->updated_at = date("Y-m-d H:i:s");
                $post->save();

                $tags = $this->filterExistingTagIds($request->tags);
                foreach ($tags as $tagId) {
                    $post->tags()->attach($tagId);
                }

                $post->save();
                return response()->json([
                    'status'=>1,
                    'message'=>'agregado ok'
                    ]);
        } catch (Exception $e) {
            echo $e;
        }
    }

    public function update(PostRequest $request)
    {
        try {
            $this->minValidationRequired($request);
                $post = Post::
                select()
                    ->where([
                        'title'=>$request->title
                        ])
                ->first();

                $post->body = $request->body;
                $post->user_id = $request->user_id;
                $post->category_id= $request->category_id;

                $post->tags()->detach();
                $tags = $this->filterExistingTagIds($request->tags);
                foreach ($tags as $tagId) {
                    $post->tags()->attach($tagId);
                }

                $post->save();
                return response()->json([
                    'status'=>1,
                    'message'=>'actualizado ok'
                    ]);
        } catch (Exception $e) {
            echo $e;
        }
    }


    public function getAll($category_id = 0,$q = "")
    {
        $query = Post::select()
        ->with('user')
        ->with('category')
        ->with('tags')
        ->orderBy("posts.created_at","asc");

        // parametros opcionales de busqueda
        if($category_id!=0){
            $query->where('category_id',$category_id);
        }

        if ($q!="") {
            // flash para navegar dentro de aninonimos
            Session::set("queryAux",$q);
            $query
                ->select('posts.*')
                ->join('post_tag','posts.id','=','post_tag.post_id')
                ->join('tags','tags.id','=','post_tag.tag_id')
                 ->where(function ($subQuery) {
                     $subQuery
                            ->orWhere('posts.title','like', '%'.Session::get("queryAux").'%')
                            ->orWhere('tags.name','=',   Session::get("queryAux"))
                            ->orWhere('posts.body','like',  '%'.Session::get("queryAux").'%');
            });

        }
        // fin parametros opcionales de busqueda

        return $query->get();
    }
}
