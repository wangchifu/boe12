@if(auth()->user()->group_id==2 or !empty(auth()->user()->section_id))
    <?php $sections = config('boe.sections'); ?>
    @if(!empty(auth()->user()->section_id))
        <?php
        $c_r = \App\Models\Report::where('user_id',auth()->user()->id)
            ->where(function($q){
                $q->where('situation','1')
                    ->orWhere('situation','2');
            })->count();
        $c_p = \App\Models\Report::where('user_id',auth()->user()->id)
            ->where(function($q){
                $q->where('situation','3')
                    ->orWhere('situation','4');
            })->count();
        ?>
        <a href="{{ route('edu_report.create') }}" class="btn btn-success btn-sm"><i class="fas fa-plus"></i> 新增[{{ $sections[auth()->user()->section_id] }}]填報</a>　
        <div class="btn-group" role="group" aria-label="Basic example">
            <a href="{{ route('edu_report.index') }}" class="btn btn-warning btn-sm"><i class="fas fa-exclamation-circle"></i> 作業區 ({{ $c_r }})</a>
            <a href="{{ route('edu_report.passing') }}" class="btn btn-secondary btn-sm"><i class="fas fa-check-circle"></i> 通過區 ({{ $c_p }})</a>
        </div>
    @else
        尚未被分科室
        @if(auth()->user()->my_section_id)
            <?php $sections = config('boe.sections'); ?>
            -->{{ $sections[auth()->user()->my_section_id] }}，等候同意
        @else
            <a href="{{ route('my_section.index') }}" class="btn btn-success btn-sm">選填我的科室</a>
        @endif
    @endif
    <?php
    $user_power = \App\Models\UserPower::where('user_id',auth()->user()->id)
        ->where('power_type','A')
        ->first();
    ?>
    @if($user_power)
        <?php
        $c_a = \App\Models\Report::where('section_id',$user_power->section_id)
            ->where('situation','1')
            ->orwhere('situation', '=', '2')
            ->count();
        $sections = config('boe.sections');
        ?>
        @if(!empty(auth()->user()->section_id) and in_array($user_power->section_id,$sections))
            　　管理填報：<a href="{{ route('reports.review') }}" class="btn btn-primary btn-sm">[{{ $sections[$user_power->section_id] }}] <i class="fas fa-user-cog"></i> 待審填報 ({{ $c_a }})</a>
        @endif
    @endif

@endif
