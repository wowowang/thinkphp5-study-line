#!/bin/bash
PATH=/usr/local/bin:/usr/bin:/bin
YM=`date +%Y%m`
FLOG=/home/www/bin/video_concat_${YM}.log
loglevel=0 #debug:0; info:1; warn:2; error:3
TIME=`date '+%Y-%m-%d %H:%M:%S'`
function LOG(){
	local log_type=$1
	local LOG_CONTENT=$2
	logformat="`date '+%Y-%m-%d %H:%M:%S'` \t[${log_type}]\tFunction: ${FUNCNAME[@]}\t[line:`caller 0 | awk '{print$1}'`]\t [log_info: ${LOG_CONTENT}]"
	{
	case $log_type in
                debug)
                        [[ $loglevel -le 0 ]] && echo -e "\033[34m${logformat}\033[0m" ;;
                info)
                        [[ $loglevel -le 1 ]] && echo -e "\033[32m${logformat}\033[0m" ;;
                warn)
                        [[ $loglevel -le 2 ]] && echo -e "\033[33m${logformat}\033[0m" ;;
                error)
                        [[ $loglevel -le 3 ]] && echo -e "\033[31m${logformat}\033[0m" ;;
        esac
        } | tee -a $FLOG
}


if [ $# -lt 5 ] ; then
	LOG error "param is not all";
	exit 1;
fi

inputpath="/home/www/videos/"
datapath="/home/www/ffmpeg_videos/"
liveId=$2;

tempfile=`date +%s`.tmp.mp4;
outputvideo=$1.mp4;
outputimage=$1.jpg


if [ ! -d $datapath ]; then
 mkdir -p $datapath;
fi

cd $datapath;

#echo '------'$(expr $# - 2); # 1
#echo '-------'$(expr $# - 1) # 2

# 【1】链接所有的 MP4 文件为一个字符串或者数组
FILES='';
for ((k=2; k<=$(expr $# - 1); k=k+2));do
        eval LIVEID=\$$k;
        eval ORIG_NAME=\$$(expr $k + 1 );
        LOG debug "LIVEID = "$LIVEID        #   L00089
        LOG debug "ORIG_NAME = "$ORIG_NAME  #   CY000098778-149518660820170519173648
        ORIG_PATH_FILE=${ORIG_NAME}.mp4;

        LOG warn "check all mp4 file  "$ORIG_PATH_FILE;     #   CY000098778-149518660820170519173648.mp4
        #检查文件是否存在
        if [ ! -f $ORIG_PATH_FILE ];then
                cp ${inputpath}${ORIG_PATH_FILE} ${ORIG_PATH_FILE}
                LOG debug "cp file get videos dir"
        fi

        if [ ! -f $ORIG_PATH_FILE ];then
            LOG debug "cp file get videos dir"
            exit 1;
        fi

        FILES=$FILES' '$ORIG_PATH_FILE;
done

prefix=$datapth`date +%F-%H-%M-%S`;
concat='concat:';
let "index = 0";
let "tsfile_count = 0 ";

# 【2】遍历所有文件,首先需要把mp4格式的文件转成ts格式
for i in $FILES
do
    LOG debug "index: "$i;      #   CY000098778-149518660820170519173648.mp4
    if [ ! -f $i ] ;then
        LOG warn "file not exist";
        exit 2001;
    else
        tempname=${prefix}_${index}".ts";
        LOG error "tempname == "$tempname   #   2017-06-23-16-29-36_0.ts
        /usr/bin/ffmpeg  -i $i -acodec copy -vcodec copy -bsf:v h264_mp4toannexb  $tempname  >/dev/null 2>&1;
        if [ $index -eq 0 ]; then
            concat=$concat${tempname};
        else
            concat=$concat"|"$tempname;
        fi
        #echo $concat;
        LOG debug "last concat: "$concat;   #   concat:2017-06-23-16-29-36_0.ts|2017-06-23-16-29-36_1.ts|2017-06-23-16-29-36_2.ts
    fi
    let "index =  index +1"  ;
    let "tsfile_count = tsfile_count + 1";
done

LOG error "concat : "$concat;   #   concat:2017-06-23-16-29-36_0.ts|2017-06-23-16-29-36_1.ts|2017-06-23-16-29-36_2.ts

# 【4】拼接好之后，再将ts封装格式转换回 mp4
ffmpeg -i "$concat" -acodec copy -vcodec copy -absf aac_adtstoasc $tempfile >/dev/null 2>&1;

# 【5】删除ts 文件
let "index = 0";
while [ $index -lt $tsfile_count ]
do
    rm ${prefix}_${index}.ts;
    let "index = index +1";
done

# 【6】赋予文件权限
if [ ! -f $tempfile ]; then
   LOG error "ffmpeg concat file failed";
   exit 1;
fi

# 【7】重名临时MP4文件为新名称的MP4视频文件
mv $tempfile $datapath$outputvideo;

if [ ! -f $datapath$outputvideo ]; then
    echo '-6';# move file error, disk is full? ;
    exit -6;
fi

# 【8】截取图片
/usr/bin/ffmpeg -analyzeduration 2147483647 -probesize 2147483647  -y -ss 00:00:10 -i $datapath$outputvideo  -vframes 1 $datapath/$outputimage  >>/dev/null 2>>/dev/null;
if [ ! -f $datapath$outputimage ]; then
    echo '-5';# create thumbnail error
    exit -5;
fi

# 【9】移动文件到指定的目录
mv $datapath$outputvideo $inputpath

mv $datapath$outputimage $inputpath

LOG info "all finished"

# 【10】定期删除已经过期的mp4 文件
cd $datapath
find ./ -mindepth 1 -maxdepth 3 -type f -name "*.mp4" -mmin +2 | xargs rm -rf
exit -11


