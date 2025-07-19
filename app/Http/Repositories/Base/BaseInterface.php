<?php

namespace App\Http\Repositories\Base;

interface BaseInterface
{
  public function findBySlug($slug);
  public function findByName($name);
  public function findById($id);
  public function delete($id);
  public function setLocales($model, $locales, $slug=false, $generatedSlug=null);
  public function setSortParams($request);
  public function getWith($models, $with);
  public function getWithCount($models, $withCount);
  public function generateSlug($model, $name, $column='slug');
  public function generatefrom($name);
  public function uploadImages($imageFile, $dir);
  public function UpdateImage ($request ,$model);
  function getIPAddress();
  public function set_History ($model, $user, $desc);
}
