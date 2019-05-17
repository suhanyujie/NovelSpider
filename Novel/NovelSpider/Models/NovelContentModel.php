<?php
namespace Novel\NovelSpider\Models;


class NovelContentModel extends AbstractModel
{
    protected $table = 'novel_content';

    protected $fillable = [
        'id',
        'novel_id',
        'list_id',
        'chapter',
        'title',
        'content',
        'date',
        'add_time',
        'update_time',
        'err_flag',
        'delete_flag',
    ];
}
