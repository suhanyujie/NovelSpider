<?php
/**
 * Created by PhpStorm.
 * User: suhanyu
 * Date: 18/8/9
 * Time: 下午10:23
 */

namespace Novel\NovelSpider\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class NovelMainModel
 * @property id
 * @property name
 * @property desc
 * @property list_url
 * @property base_url
 * @property novel_status
 * @property insert_date
 * @property update_time
 * @package Novel\NovelSpider\Models
 */
class NovelMainModel extends Model
{
    protected $table = 'novel_main';

    protected $fillable = [
        'id',
        'name',
        'desc',
        'list_url',
        'base_url',
        'novel_status',
        'insert_date',
        'update_time',
    ];

    const UPDATED_AT = null;
    const CREATED_AT = null;
}
