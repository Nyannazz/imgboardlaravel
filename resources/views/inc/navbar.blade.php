<nav class="navBarFixed">
    <div class="container flex">
        <a class="navbar-brand" href="/">{{config('app.name','myApp')}}</a>
        <button onclick='openNav()' class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item active">
                    <a class="nav-link" href="/">Home <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/about">Features</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/posts">Pricing</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link disabled" href="/controller">Disabled</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<script>
    function openNav() {
        console.log('hey')
    }

</script>
