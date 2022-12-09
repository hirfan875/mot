<style>
    #imageUpload
{
    display: none;
}

#profileImage
{
    cursor: pointer;
}

#profile-container {
   -webkit-border-radius: 50%;
    -moz-border-radius: 50%;
    -ms-border-radius: 50%;
    -o-border-radius: 50%;
    border-radius: 50%;
    position: absolute;
    top: 126px;
    right: 100px;
}
#profileImage1 {
    width: 100px;
        height:100px;
    -webkit-border-radius: 50%;
    -moz-border-radius: 50%;
    -ms-border-radius: 50%;
    -o-border-radius: 50%;
    border-radius: 50%;
}

#profile-container img {
    width: 20px;
    height: 20px;
}
#profileImage {
    font-size: 20px;
}
</style>
<div class="avatar">
    @if($customer->image)
    <img  id="profileImage1" src="{{asset('storage/'.$customer->image)}}" alt="{{ getAvatarCode($customer->name) }}" height="100" />
    @else
        <span class="avatar-in" > {{ getAvatarCode($customer->name) }}</span>
    @endif
    <form  enctype="multipart/form-data" id="imageUploadForm" >
        @csrf
        <div id="profile-container">
            <span id="profileImage"><i class="fa fa-edit"></i></span>
            <input id="imageUpload" type="file"  name="image" placeholder="Choose image" required="" >
            @error('image')
                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
            @enderror
        </div>
    </form>
</div>
<div class="content mt-2 mb-4">
    <h2>{{$customer->name}}</h2>
    <span>{{$customer->phone}}</span>
</div>