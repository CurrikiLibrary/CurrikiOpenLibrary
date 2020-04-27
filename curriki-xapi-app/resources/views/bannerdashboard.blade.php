<div class="container mb-4 pb-2">
	<div class="shadow-box p-3 px-md-5">
		<div class="row">
			<div class="col-xl-7 col-md-6">
				<div class="media mb-4 mb-md-0">
					<div class="user-thumbnail mr-3 ml-xl-3">
						<img class="img-fluid rounded-circle" src="{{asset('images/user-thumbnail.jpg')}}" width="140" height="140" alt="user">
						<i class="fa fa-bolt"></i>
					</div>
					<div class="media-body">
						<div class="user-data">
							<div class="user-name mb-2">Bonnie Brown</div>
							<div class="user-desc font-size-16 mb-1">11th Grade</div>
							<div class="user-xp"><a href="#" class="student-score"><div class="pie-wrapper" data-percent="60">5</div> 750XP</a></div></div>	
					</div>
				</div>			
			</div>
			<div class="col-xl-5 col-md-6">
				<ul class="list-inline list-stat d-md-flex justify-content-between">
					<li class="list-inline-item"><i class="icon-std icon-comet"></i> <span class="stat-counter">11</span> <span class="item">Novice</span></li>
					<li class="list-inline-item"><i class="icon-std icon-moons"></i> <span class="stat-counter">8</span> <span class="item">Explorer</span></li>
					<li class="list-inline-item"><i class="icon-std icon-planets"></i> <span class="stat-counter">5</span> <span class="item">Expert</span></li>
					<li class="list-inline-item"><i class="icon-std icon-constellation"></i> <span class="stat-counter">1</span> <span class="item">Master</span></li>
				</ul>
			</div>				
		</div>
	</div>
</div>

<!-- Modal -->
<div class="modal fade" id="ladderModal" tabindex="-1" role="dialog" aria-labelledby="ladderModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="ladderModalLabel">My Leaderboard</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <img src="{{ asset('images/ladder.png') }}">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
@section('styles')
	<style type="text/css">
		.student-score{
			text-decoration: none;
			color:gray;
		}
	</style>
@endsection
@section('scripts')
	<script type="text/javascript">
		$(document).ready(function(){
			$('.student-score').on('click', function(){
				$('#ladderModal').modal();
			});
		});
	</script>
@endsection