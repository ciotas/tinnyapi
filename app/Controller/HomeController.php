<?php
/**
* \HomeController
*/
namespace App\Controller;

use ERedis as Redis;

class HomeController extends BaseController {

  public function home()
  {
    // var_dump(Article::count());
    $data = ['title'=>'123', 'email'=>'zhangxinxin@zhufaner.com'];
    $validator = $this->validate($data, [
      'title' => 'required|numeric|integer|min:3|max:4',
      'email' => 'required|email',
    ]);
    if ( !$validator->success ) {
      foreach ($validator->errors as $error) {
        echo $error.'<br>';
      }
    }
      // redis sample
      Redis::set('key','value',3000,'ms');
      echo Redis::get('key');
    // Log::debug('First Debug Info.');
    /*
    // mail sample
    Mail::to('foo@bar.io')->from('bar@foo.io')
                          ->title('Foo Bar')
                          ->content('<h1>Hello~~</h1>')
                          ->send();

    */

    // // return View
    // return View::make('home')->with('article',Article::first())
    //                           ->withTitle('TinyLara :-D')
    //                           ->withFooBar('foo_bar');

  }

}