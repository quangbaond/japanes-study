<?php
 namespace App\Http\ViewComposers;

use App\Repositories\Admin\Managers\ProfileRepository;
use Illuminate\View\View;

 class SidebarComposer
 {
     public $numberOfLesson;
     protected $profileRepo;
     protected $avatar_image;
     protected $zoom_link;
     /**
      * Create a movie composer.
      *
      * @return void
      */
     public function __construct(ProfileRepository $profileRepo)
     {
        $this->profileRepo = $profileRepo;
        $this->numberOfLesson = $this->profileRepo->getNumberOfLesson();
        $this->zoom_link = $this->profileRepo->getCurrentZoomLink();
        $this->zoom_link = $this->zoom_link->zoom_link ?? null;
        $this->avatar_image = $this->profileRepo->getAvatarImageOfTeacher();
     }

     /**
      * Bind data to the view.
      *
      * @param  View  $view
      * @return void
      */
     public function compose(View $view)
     {
        $view->with('numberOfLesson',$this->numberOfLesson)->with('avatar_image',$this->avatar_image)->with('zoom_link',$this->zoom_link);
     }
 }
