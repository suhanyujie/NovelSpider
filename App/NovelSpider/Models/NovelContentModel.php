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
        'worker_id',
        'date',
        'err_flag',
    ];

    const UPDATED_AT = null;
    const CREATED_AT = null;
}
