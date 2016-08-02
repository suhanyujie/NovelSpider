<?php
namespace Novel\NovelSpider\Models;


class ContentModel extends AbstractModel
{
    protected $table = 'novel_content';
    protected $fillable = [
        'id', 'list_id', 'title', 'content', 'worker_id','date','err_flag',
    ];




}//  end of class