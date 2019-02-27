//円グラフ
const aCtx = $('#female_30')
new Chart(aCtx, {
    type:'pie',
    data: {


        labels: ['食事','交通費','雑費','旅費'],
        datasets: [{
            data:[40,15,20,60],
            //値 labelsに指定した順番に紐ずく
            //グラフの色
            backgroundColor: [

            'rgba(100, 100, 100, 0.1)',
            'rgba(50, 50, 50, 0.2)',
            'rgba(25, 25, 25, 0.2)',
            'rgba(12, 12, 12, 0.2)',
            ],
        }]
    },
    options: {
      title: {
        display: true,
        text: '出費割合'
       }
    }
})