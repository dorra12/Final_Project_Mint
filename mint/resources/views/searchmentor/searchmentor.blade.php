@extends('layouts.app')
@section('css')
<link href="{{asset('css/searchmentor.css')}}" rel="stylesheet">
@endsection
@section('content')

<div id="loaderContainer">
    <div id="loaderPart" class="loader"></div>
</div>

<section class="globalWidth flex">
    <section id="searchField" class="fontSize">
        <h4 class="SYM">Search your mintor</h4>
        <div class="row">
            <div class="col s10">
              <div class="row">
                <div class="input-field col s12">
                  <i class="material-icons prefix">search</i>
                  <input type="text" id="language-input" class="autocomplete">
                  <label for="language-input">Languages</label>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col s10">
              <div class="row">
                <div class="input-field col s12">
                  <i class="material-icons prefix">search</i>
                  <input type="text" id="technologie-input" class="autocomplete">
                  <label for="technologie-input">Technologie</label>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col s10">
              <div class="row">
                <div class="input-field col s12">
                  <i class="material-icons prefix">search</i>
                  <input type="text" id="name-input" class="autocomplete">
                  <label for="name-input">Name</label>
                </div>
              </div>
            </div>
          </div>
    </section>
    <section class="cardBlock height">
        <!-- CLONE ELEMENT -->
            <div id="clone" class="hide cardBGC flex2">
                <img id="img" src="" style="width:60px">
                <p id="mentorName" class="fontSize"></p>
                <p id="language"></p>
                <p id="mentroScore" class="fontSize2"></p>
                <span id="skill" class="fontSize2"></span>
                <button class="btn buttonColorVP margin" type="submit" id="goToMentorProfile" name="goToMentorProfile" value="">View profile</button>
                <button class="btn buttonColorAPP margin" type="submit" id="goToApply" name="goToApply" value="">Apply to mintor</button>
            </div>
        <!-- CLONE ELEMENT -->
        <section id="mentorList" class="height">
        </section>
    </section>
</section>

@endsection
@section('script')
<script>
$(document).ready(function () {
    elem = $("#clone");
    $( "#loaderPart" ).addClass( "loader" );
    $( "#loaderPart" ).removeClass( "hide" );
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        },
        data: {
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        async: false,
    });
    //? Get and put the data for language
    routeUrlLanguages = "{{url('')}}/initSearchLanguages";
    $.ajax({
        url: routeUrlLanguages,
        method: 'GET',
        dataType: 'json',
        success: function (result) {
            $.each(result, function(i, item) {
                langData = {};
                $.each(result[i], function(a, atem){
                    datas = result[i][a].languages;
                    langData [datas] = null;
                })
            });
            //? Add language to autocomplete
            $('#language-input').autocomplete({
                data: langData,
            });
        }
    })
    //? Get and put the data for skill
    routeUrlSkills = "{{url('')}}/initSearchSkills";
    $.ajax({
        url: routeUrlSkills,
        method: 'GET',
        dataType: 'json',
        success: function (result) {
            $.each(result, function(i, item) {
                techData = {};
                $.each(result[i], function(a, atem){
                    datas = result[i][a].skill;
                    techData [datas] = null;
                })
            });
            //? Add skills to autocomplete
            $('#technologie-input').autocomplete({
                data: techData,
            });
        }
    })
    //? Get and put the data for mentor name
    routeUrlName = "{{url('')}}/initSearchNames";
    $.ajax({
        url: routeUrlName,
        method: 'GET',
        dataType: 'json',
        success: function (result) {
            $.each(result, function(i, item) {
                nameData = {};
                $.each(result[i], function(a, atem){
                    datas = result[i][a].lastname;
                    nameData [datas] = null;
                })
            });
            //? Add name to autocomplete
            $('#name-input').autocomplete({
                data: nameData,
            });
        }
    })
    //? Get and create mentor card using function
    routeUrlName = "{{url('')}}/initSearchMentorData";
    $.ajax({
        url: routeUrlName,
        method: 'GET',
        dataType: 'json',
        success: function (result) {
            // console.log(result);
            $('#mentorList').html('');
            $.each(result, function(i, item) {
                nameData = {};
                $.each(result[i], function(a, atem){
                    //*INIT VARIABLES
                    imgUrl = "{{asset('img/')}}/"+result[i][a].profile_image;
                    //TODO Check also in other link !!!
                    mentorProfile = "{{url('')}}/mentor/"+result[i][a].user_id;
                    applyToMentor = "{{url('')}}/mentor/apply/"+result[i][a].user_id;
                    getRatingMentorAvg = "{{url('')}}/getRatingByMentor/"+result[i][a].user_id;
                    avgRating = 0;

                    //*AJAX Call for getting all rating and have the average
                    $.ajax({
                        url: getRatingMentorAvg,
                        method: 'GET',
                        dataType: 'json',
                        success: function (result) {
                            avgRating = result.rating;
                        }
                        })
                    //* CLONE THE CARD
                    clone = elem.clone(true);
                    clone.find('#img').attr('src', imgUrl);
                    clone.find('#mentorName').text(result[i][a].firstname+" "+result[i][a].lastname);
                    clone.find('#skill').text(result[i][a].skill);
                    clone.find('#mentroScore').text("Rating : "+avgRating+"/5");
                    clone.find('#language').text(result[i][a].languages);
                    clone.find('#goToMentorProfile').val(mentorProfile);
                    clone.find('#goToApply').val(applyToMentor);
                    //TODO : Add a remove class to unhide the card
                    clone.removeClass( "hide" );
                    clone.appendTo('#mentorList');
                })
            });
        }
    })
    $( "#loaderPart" ).addClass( "hide" );
    $( "#loaderPart" ).removeClass( "loader" );

    //? Evenlistener to check the search field
    $('#searchField').change(function (){
        $( "#loaderPart" ).addClass( "loader" );
        $( "#loaderPart" ).removeClass( "hide" );

        routeUrlName = "{{url('')}}/initSearchMentorData";
        initLanguageVal = $('#language-input').val();
        initSkillVal = $('#technologie-input').val();
        initNameVal = $('#name-input').val();

        $.ajax({
        url: routeUrlName,
        method: 'GET',
        data: {lang: initLanguageVal, skill: initSkillVal, name: initNameVal}, 
        dataType: 'json',
        success: function (result) {
            $('#mentorList').html('');
            $.each(result, function(i, item) {
                nameData = {};
                $.each(result[i], function(a, atem){
                    //*INIT VARIABLES
                    imgUrl = "{{asset('img/')}}/"+result[i][a].profile_image;
                    //TODO Check also in other link !!!
                    mentorProfile = "{{url('')}}/mentor/"+result[i][a].user_id;
                    applyToMentor = "{{url('')}}/mentor/apply/"+result[i][a].user_id;
                    getRatingMentorAvg = "{{url('')}}/getRatingByMentor/"+result[i][a].user_id;
                    avgRating = 0;

                    //*AJAX Call for getting all rating and have the average
                    $.ajax({
                        url: getRatingMentorAvg,
                        method: 'GET',
                        dataType: 'json',
                        success: function (result) {
                            avgRating = result.rating;
                        }
                        })
                    //* CLONE THE CARD
                    clone = elem.clone(true);
                    clone.find('#img').attr('src', imgUrl);
                    clone.find('#mentorName').text(result[i][a].firstname+" "+result[i][a].lastname);
                    clone.find('#skill').text(result[i][a].skill);
                    clone.find('#mentroScore').text("Rating : "+avgRating+"/5");
                    clone.find('#language').text(result[i][a].languages);
                    clone.find('#goToMentorProfile').val(mentorProfile);
                    clone.find('#goToApply').val(applyToMentor);
                    //TODO : Add a remove class to unhide the card
                    clone.removeClass( "hide" );
                    clone.appendTo('#mentorList');
                })
            })
        }
        })
        $( "#loaderPart" ).addClass( "hide" );
        $( "#loaderPart" ).removeClass( "loader" );
    })

    //? even to go to mentor profile
    $("button[name='goToMentorProfile']").click(function (event){
        event.preventDefault();
        routeUrl = $(this).val();
        window.location.href = routeUrl;
    })
    //? even to go to apply mentor profile
    $("button[name='goToApply']").click(function (event){
        event.preventDefault();
        routeUrl = $(this).val();
        window.location.href = routeUrl;
    })


});
</script>
@endsection