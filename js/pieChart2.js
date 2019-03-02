var array = <?php echo json_encode($data, category1 | category2 | category3 | category4 | category5 | category6 ); ?>;
        console.log(array);
const pCtx = $('#pieChart2')
new Chart(pCtx, {
    type:'pie',
    data: {


        labels: ['食費','交通費','生活費','交際費','衣服・美容','趣味・娯楽'],
        datasets: [{
            data:array,
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