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
    protected $table = 'novel_main';

    protected $fillable = [
        'id',
        'list_url',
        'base_url',
        'novel_status',
        'desc',
        'insert_date',
    ];

    const UPDATED_AT = null;
    const CREATED_AT = null;
}
