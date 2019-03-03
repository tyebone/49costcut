
// data = <?php echo json_encode($data2) ?>


// const pCtx = $('#pieChart2')
// new Chart(pCtx, {
//     type:'pie',
//     data: {


//         labels: ['食事','交通費','生活費','交際費','衣服・美容','趣味・娯楽'],
//         datasets: [{
//             data:[40,15,20,60,10,49],
//             //値 labelsに指定した順番に紐ずく
//             //グラフの色
//             backgroundColor: [

//             'rgba(100, 100, 100, 0.1)',
//             'rgba(50, 50, 50, 0.2)',
//             'rgba(25, 25, 25, 0.2)',
//             'rgba(12, 12, 12, 0.2)',
//             ],
//         }]
//     },
//     options: {
//       title: {
//         display: true,
//         text: '出費割合'
//        }
//     }
// })

const bCtx = $('#pieChart2')
new Chart(bCtx,{
  type: 'pie',
  data: {
    labels:['食事','交通費','生活費','交際費','衣服・美容','趣味・娯楽'],
    datasets: [{
      data: [40,15,20,60,10,49],
      backgroundColor:[
        'rgba(255,91,162,0.8)',
        'rgba(54,162,233,0.8)',
        'rgba(255,206,86,0.8)',
        'rgba(275,99,132,0.8)',
        ],
    }]
  },
  options:{
    title:{
      display: true,
      text: '出費割合'
    }

  }
})



