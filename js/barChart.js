
//phpを所得
var array = <?php echo json_encode($jsonstr); ?>;

//仮出力
console.log(array);

const bCtx = $('#barChart');
//divのバーチャートに２次元で作成  btx
var btx = document.getElementById("barChart").getContext('2d');

// 新規チャート作成
var myChart = new Chart(bCtx, {
    type:'line',
	data: {
      labels: ['貧乏神','ひもじい', '浪費家', 'ノーマル', 'ちょっとしたすごいやつ', '毎月貯金', '金持ち金持ちコース'],
      datasets: [{
	 	label: '最高気温(度）',
	    data: array,
	    borderColor: "rgba(255,0,0,1)",
	    backgroundColor: "rgba(0,0,0,0,0,0)"
	    },
	  ],
},
	options: {
		scales: {
			yAxes: [{
				ticks: {

					//グラフの最小値を0にする
					beginAtZero: true
				}
			}]
		}
	}
})