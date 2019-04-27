<?php
/**
 * Created by PhpStorm.
 * User: suhanyu
 * Date: 18/8/9
 * Time: 下午10:23
 */

namespace Novel\NovelSpider\Models;

use Illuminate\Database\Eloquent\Model;

class NovelMainModel extends Model
{
    protected $fillable = [
        'id',
        'name',
        'desc',
        'list_url',
        'base_url',
        'novel_status',
        'insert_date',
    ];

    protected $table = 'novel_main';

    const UPDATED_AT = null;
    const CREATED_AT = null;
}
