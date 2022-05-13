
import moment from 'moment';
export default {
    methods: {
        diffTime(time){
            var nowTime = moment(new Date());
            var diffTime = nowTime.diff(time);

            if(diffTime < (1000 * 60)){
                return nowTime.diff(time, 'seconds') + '秒前';
            }
            else if(diffTime < (1000 * 60 * 60)){
                return nowTime.diff(time, 'minutes') + '分前';
            }
            else if(diffTime < (1000 * 60 * 60 * 24)){
                return nowTime.diff(time, 'hours') + '時間前';
            }
            else{
                return nowTime.diff(time, 'days') + '日前';
            }

        }
    }
}
