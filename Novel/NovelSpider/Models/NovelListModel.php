<?php
/**
 * Created by PhpStorm.
 * User: suhanyu
 * Date: 18/7/31
 * Time: 下午9:08
 */

namespace Novel\NovelSpider\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Capsule\Manager as Capsule;

/**
 * Class NovelListModel
 * @property-write id
 * @property-write novel_id
 * @property-write url
 * @property-write flag
 * @property-write err_flag
 * @property-write add_time
 * @property-write update_time
 * @package Novel\NovelSpider\Models
 */
class NovelListModel extends Eloquent
{
    /**
     * @var Capsule
     */
    protected $capsule;

    protected $table = 'novel_list';

    protected $fillable = [
        'id',
        'novel_id',
        'name',
        'url',
        'flag',
        'err_flag',
        'add_time',
        'update_time',
    ];

    const UPDATED_AT = null;
    const CREATED_AT = null;

    // 小说章节未抓取状态
    const FLAG_CHAPTER_NOT_CRAWL = 2;
    // 小说章节已抓取状态
    const FLAG_CHAPTER_HAS_CRAWL = 1;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->capsule = $capsule = new Capsule();
    }

    public function insertMul($dataArr = [])
    {
        return $this->capsule::table($this->table)->insert($dataArr);
    }

    public function getList($paramArr=[])
    {
        $options = [
            'id'       => '',//
            'novel_id' => '',
            'flag'     => '',
            'fields'   => '*',// string 查询字段
            'isCount'  => '',// 可选：1 是否只返回数据的数量
            'debug'    => '',// 可选：1 调试，为true时，打印出sql语句
            'orders'   => [],// ['id'=>'desc',  ]
            'offset'   => 0,// 可选 int mysql查询数据的偏移量
            'limit'    => 1,// 可选 int mysql查询数据的条数限制
        ];
        is_array($paramArr) && $options = array_merge($options, $paramArr);
        extract($options);
        $model = new self();
        if (!empty($options['id'])) {
            if (is_array($options['id'])) {
                $model = $model->whereIn('id', $options['id']);
            } else {
                $model = $model->where('id', $options['id']);
            }
        }
        if (!empty($options['novel_id'])) {
            $model = $model->where('novel_id', $options['novel_id']);
        }
        if (!empty($options['flag'])) {
            $model = $model->where('flag', $options['flag']);
        }
        if (!empty($options['isCount'])) {
            return $model->count();
        }
        // 排序
        if (!empty($options['orders'])) {
            foreach ($options['orders'] as $orderField => $orderDir) {
                $model = $model->orderby($orderField, $orderDir);
            }
        } else {
            $model = $model->orderby('id', 'desc');
        }
        $model = $model->offset($options['offset'])->limit($options['limit']);
        if (!empty($debug)) {
            echo $model->toSql() . "--------------debug\n";
        }
        $data = $model->get([$options['fields']])
                    ->toArray();

        return $data;
    }
}
