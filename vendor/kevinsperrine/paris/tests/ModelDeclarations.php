<?php

class Simple extends Model
{
}

class ComplexModelClassName extends Model
{
}

class ModelWithCustomTable extends Model
{
    public static $_table = 'custom_table';
}

class ModelWithCustomTableAndCustomIdColumn extends Model
{
    public static $_table = 'custom_table';
    public static $_id_column = 'custom_id_column';
}

class ModelWithFilters extends Model
{
    public static function name_is_fred($orm)
    {
        return $orm->where('name', 'Fred');
    }

    public static function name_is($orm, $name)
    {
        return $orm->where('name', $name);
    }
}

class Widget extends Model
{
}

class Profile extends Model
{
    public function user()
    {
        return $this->belongsTo('User');
    }
}

class User extends Model
{
    public function profile()
    {
        return $this->hasOne('Profile');
    }
}

class UserTwo extends Model
{
    public function profile()
    {
        return $this->hasOne('Profile', 'my_custom_fk_column');
    }
}

class ProfileTwo extends Model
{
    public function user()
    {
        return $this->belongsTo('User', 'custom_user_fk_column');
    }
}

class Post extends Model
{
}

class UserThree extends Model
{
    public function posts()
    {
        return $this->hasMany('Post');
    }
}

class UserFour extends Model
{
    public function posts()
    {
        return $this->hasMany('Post', 'my_custom_fk_column');
    }
}

class Author extends Model
{
}

class AuthorBook extends Model
{
}

class Book extends Model
{
    public function authors()
    {
        return $this->hasManyThrough('Author');
    }
}

class AuthorTwo extends Model
{
}

class WroteTheBook extends Model
{
}

class BookTwo extends Model
{
    public function authors()
    {
        return $this->hasManyThrough('AuthorTwo', 'WroteTheBook', 'custom_book_id', 'custom_author_id');
    }
}
