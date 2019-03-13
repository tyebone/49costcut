// 円グラフ
Chart.defaults.global.defaultFontColor = 'black';
const pCtx = $('#pieChart')
function showPie(type) {
new Chart(pCtx,{
  type: 'pie',
  data: {
    labels:['29歳以下男性','29歳以下女性','30歳以上男性','30歳以上女性'],
    datasets: [{
      data: type,
      backgroundColor:[
        '#1e90ff',
        '#dc143c',
        '#191970',
        '#c71585',
        ],
    }]
  },
  options:{
    title:{
      display: true,
      text: '年代、性別ごとの利用者数'
    }

  }
})
}
