<?php
/**
 * Created by PhpStorm.
 * User: suhanyu
 * Date: 18/8/3
 * Time: 上午8:34
 */

namespace Novel\NovelSpider\Models;


use Illuminate\Database\Eloquent\Model;

class NovelContentModel extends Model
{
    protected $table = 'novel_content';

    protected $fillable = [
        'id',
        'list_id',
        'chapter',
        'title',
        'content',
        'err_flag',
        'add_time',
        'update_time',
    ];

    const UPDATED_AT = null;
    const CREATED_AT = null;
}
