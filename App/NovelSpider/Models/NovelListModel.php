<?php
/**
 * Created by PhpStorm.
 * User: suhanyu
 * Date: 18/7/31
 * Time: 下午9:08
 */

namespace Novel\NovelSpider\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

class NovelListModel extends Eloquent
{
    protected $table = 'novel_list';

    protected $fillable = [
        'id',
        'novel_id',
        'url',
        'flag',
        'err_flag',
    ];

    const UPDATED_AT = null;
    const CREATED_AT = null;

    public function getList($paramArr=[])
    {
        $options = [
            'id'      => '',//
            'novel_id'=>'',
            'flag'=>'',
            'fields'  => '*',// string 查询字段
            'isCount' => '',// 可选：1 是否只返回数据的数量
            'debug'   => '',// 可选：1 调试，为true时，打印出sql语句
            'offset'  => 0,// 可选 int mysql查询数据的偏移量
            'limit'   => 1,// 可选 int mysql查询数据的条数限制
        ];
        is_array($paramArr) && $options = array_merge($options, $paramArr);
        extract($options);
        $model = new self();
        if (!empty($id)) {
            if (is_array($id)) {
                $model = $model->whereIn('id', $id);
            } else {
                $model = $model->where('id', $id);
            }
        }
        if (!empty($novel_id)) {
            $model = $model->where('novel_id', $novel_id);
        }
        if (!empty($flag)) {
            $model = $model->where('flag', $flag);
        }
        if (!empty($isCount)) {
            return $model->count();
        }
        //order
        if (!empty($order)) {
            foreach ($order as $orderField => $orderDir) {
                $model = $model->orderby($orderField, $orderDir);
            }
        } else {
            $model = $model->orderby('id', 'desc');
        }
        $model = $model->offset($offset)->limit($limit);
        if (!empty($debug)) {
            echo $model->toSql();exit();
        }
        $data = $model->get([$fields]);

        return $data;
    }
}
