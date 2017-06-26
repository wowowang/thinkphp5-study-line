#!/usr/bin/env bash
PATH=/usr/local/bin:/usr/bin:/bin
YM=`date +%Y%m`
FLOG=/home/www/bin/video_cut_${YM}.log
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

if [ $# -ne 5 ] ; then
        LOG error "param is not all";
        exit 1;
fi

LOG info "all param $1 $2 $3 $4 $5 $6 ";

inputpath="/home/www/videos/"
datapath="/home/www/ffmpeg_videos/"
liveId=$1;
videofile=$2.mp4;

starttime=$3;
endtime=$4;
tempfile=$datapath`date +%s`.tmp.mp4;
outputfile=$5;
outputvideo=$5.mp4;
outputimage=$5.jpg;
outputpng=$5.png;
actualLiveId=$6;

if [ ! -d $datapath ]; then
        mkdir -p $datapath;
fi

if [ ! -f $datapath$videofile ]; then
    cp ${inputpath}${videofile} ${datapath}${videofile}
fi

#datapath
if [ ! -f $datapath$videofile ];then
        LOG error " mv file failed";
        exit 1;
fi

FFMPEG_CUT_FILE=$(/usr/bin/ffmpeg -i $datapath$videofile -vcodec copy -acodec copy -ss $starttime -to $endtime  $tempfile -y >>/dev/null 2>>/dev/null  && echo "success" || echo "fail")
LOG debug "ffmpeg cut file : ${FFMPEG_CUT_FILE} "
#ffmpeg -i $datapath$videofile -vcodec copy -acodec copy -ss $starttime -to $endtime  $tempfile -y;

if [ ! -f $tempfile ]; then
   LOG error " ffmpeg cut  file failed";
   exit 1;
fi

mv $tempfile $datapath$outputvideo;

if [ ! -f $datapath$outputvideo ]; then
    LOG error "move file error, disk is full";
    exit 1;
fi

FFMPEG_CUT_IMAGE_JPG=$(/usr/bin/ffmpeg -y -ss 00:00:10 -i $datapath$outputvideo  -vframes 1 $datapath$outputimage >>/dev/null 2>>/dev/null && echo "success" || echo "fail")
LOG debug "ffmpeg cut jpeg image : ${FFMPEG_CUT_IMAGE_JPG} "
#ffmpeg -y -ss 00:00:10 -i $datapath$outputvideo  -vframes 1 $datapath$outputimage  >>/dev/null 2>>/dev/null;

if [ ! -f $datapath$outputimage ]; then
        FFMPEG_CUT_IMAGE_PNG=$(/usr/bin/ffmpeg -y -ss 00:00:10  -analyzeduration 2147483647 -probesize 2147483647 -i $datapath$outputvideo  -vframes 1 $datapath$outputpng >>/dev/null 2>>/dev/null && echo "success" || echo "fail")
        LOG debug "ffmpeg cut png image : ${FFMPEG_CUT_IMAGE_PNG} "
        #ffmpeg -y -ss 00:00:10  -analyzeduration 2147483647 -probesize 2147483647 -i $datapath$outputvideo  -vframes 1 $datapath$outputpng ;  >>/dev/null 2>>/dev/null;
        convert $datapath$outputpng $datapath$outputimage >>/dev/null 2>>/dev/null;
fi

mv $datapath$outputvideo $inputpath
mv $datapath$outputimage $inputpath
cd $datapath
find ./ -mindepth 1 -maxdepth 3 -type f -name "*.png" -mmin +2 | xargs rm -rf
find ./ -mindepth 1 -maxdepth 3 -type f -name "*.mp4" -mmin +2 | xargs rm -rf
LOG info "all finish";