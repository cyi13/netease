<?php
namespace App\Repositories\Implement;
use App\Repositories\Interfaces\CrawlersInterface;
use App\Repositories\Common;

class CrawlersRepository extends Common implements CrawlersInterface{

    /**
     * 获取网易云的歌单分类并插入到数据库
     * @return bollean
     */
    public function getMusicList(){
        
        //抓取出来的放到txt文件中去
        $filePath    = './crawlers/';
        $fileAddress = './crawlers/cloudMusicCategory.txt';
        if(file_exists($fileAddress)){
        	$CategoryPage = file_get_contents($fileAddress);
        }else{
        	$url = "http://music.163.com/discover/playlist/?order=hot";
        	$CategoryPage = $this->sendCurl($url);
            if(!is_dir($filePath)){
                mkdir($filePath);
            }
            file_put_contents($fileAddress,$CategoryPage);
        }

        $rule                 = '#<dt><i class="u-icn u-.*"></i>(.*)</dt>([\s\S]*?)</dd>#';
        $list                 = $this->pregMathAll($rule,$CategoryPage);
        //歌单的第一级分类
        $firstCategoryList    =  $list[0];
        //包含第二级分类的字符串
        $secondCategoryString = $list[1];       
        $categoryList         = array();
        //简单判断一下总的分类数量 便于判定是否要更新数据库
        $totalCateNum         = count($firstCategoryList);
        //获取第二级分类 将第一级和第二级对应起来
        foreach ($secondCategoryString as $key => $value) {
            if(!isset($categoryList[$key]['firstCategory'])){
                $categoryList[$key]['firstCategory'] = $firstCategoryList[$key];
            }
            $rule   = '|<a class="s-fc1 " href="(.*)" data-cat="(.*)">.*</a><span class="line">|';
            $result = $this->pregMathAll($rule,$value);
            if(!empty($result)){
                $hrefList  = $result[0];
                $titleList = $result[1];
                foreach ($hrefList as $k => $v) {
                    //获得的第二级分类名称和对应的URL
                    $newList[$k]['href']     = 'http://music.163.com'.$v;
                    $newList[$k]['cateName'] = $titleList[$k]; 
                }
                //第二级的分类也算进去
                $totalCateNum += count($newList);
            }
            $categoryList[$key]['secondCategory'] = $newList;
        }
        //数据表模型
        $model = new \App\Models\CloudMusicCategory;
        //数据表中存在的数量
        $count = $model->count();

        //抓取出来的数量大于数据表中已有的就重新生成
        if(intval($count) < intval($totalCateNum)){
            
            //统计一下插入的数量
            $totalSuccessNum = 0;
            $totalError = array();
            //清空一下数据表
            $model->truncate();
            //插入数据库
            foreach($categoryList as $key=>$value){
                //获取模型
                
                $data = array('cateName'=>$value['firstCategory']);
                $res = $model->create($data);

                if(empty($res)){
                    $totalError[] = array('cateName'=>$value['firstCategory'],);
                }else{
                    $totalSuccessNum ++;
                    $cateId = $res->cateId;
                    $cateName = $res->cateName;
                    foreach ($value['secondCategory'] as $k => $v) {
                       $data = array('cateName'       => $v['cateName'],
                                     'parentCateId'   => $cateId,
                                     'parentCateName' => $cateName,
                                     'link'           => $v['href']);
                       $res = $model->create($data);
                       if(empty($res)){
                            $totalError[] = array('cateName'=>$v['cateName'],'parentCate'=>$value['firstCategory']);
                       }else{
                            $totalSuccessNum ++;
                       }
                    }
                }
            }
            return array('totalSuccessNum'=>$totalSuccessNum,'totalError'=>$totalError);
        }
        return '数据已经存在，无须更新';
        
    }

}
