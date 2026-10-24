<div class="row">

    <!-- Card 1: Total de Produtos -->
    <div class="col-12 col-md-4 col-lg-2">
        <div class="small-box bg-blue">
            <div class="inner">
                <h3>{{ number_short($totalProduct) }}</h3>
                <p>Total de Produtos</p>
            </div>
            <div class="icon">
                <i class="fa-solid fa-boxes-stacked"></i>
            </div>
            <a href="{{ route('product') }}" class="small-box-footer">
                Mais detalhes <i class="fa-solid fa-circle-arrow-right"></i>
            </a>
        </div>
    </div>

    <!-- Card 2: Categorias -->
    <div class="col-12 col-md-4 col-lg-2">
        <div class="small-box bg-navy">
            <div class="inner">
                <h3>{{ number_short($totalCategory) }}</h3>
                <p>Categorias Activas</p>
            </div>
            <div class="icon">
                <i class="fa-solid fa-tags"></i>
            </div>
            <a href="{{ route('product') }}" class="small-box-footer">
                Mais detalhes <i class="fa-solid fa-circle-arrow-right"></i>
            </a>
        </div>
    </div>

    <!-- Card 3: Vendas do Dia -->
    <div class="col-12 col-md-4 col-lg-2">
        <div class="small-box bg-green">
            <div class="inner">
                <h3>42</h3>
                <p>Vendas do Dia</p>
            </div>
            <div class="icon">
                <i class="fa-solid fa-cash-register"></i>
            </div>
            <a href="{{ route('reports') }}" class="small-box-footer">
                Mais detalhes <i class="fa-solid fa-circle-arrow-right"></i>
            </a>
        </div>
    </div>

    <!-- Card 4: Lucro do Mês -->
    <div class="col-12 col-md-4 col-lg-2">
        <div class="small-box bg-aqua">
            <div class="inner">
                <h3>3.850.400</h3>
                <p>Lucro do Mês (Kz)</p>
            </div>
            <div class="icon">
                <i class="fa-solid fa-chart-line"></i>
            </div>
            <a href="{{ route('reports') }}" class="small-box-footer">
                Mais detalhes <i class="fa-solid fa-circle-arrow-right"></i>
            </a>
        </div>
    </div>

    <!-- Card 5: Produtos em Stock Baixo -->
    <div class="col-12 col-md-4 col-lg-2">
        <div class="small-box bg-red">
            <div class="inner">
                <h3>{{ number_short($totalProductCrit) }}</h3>
                <p>Stock Crítico</p>
            </div>
            <div class="icon">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <a href="{{ route('product') }}" class="small-box-footer">
                Verificar stock <i class="fa-solid fa-circle-arrow-right"></i>
            </a>
        </div>
    </div>

    <!-- Card 6: Clientes Registados -->
    <div class="col-12 col-md-4 col-lg-2">
        <div class="small-box bg-yellow">
            <div class="inner">
                <h3>{{ number_short($totalCustomers) }}</h3>
                <p>Clientes Registados</p>
            </div>
            <div class="icon">
                <i class="fa-solid fa-users"></i>
            </div>
            <a href="{{ route('customers') }}" class="small-box-footer">
                Mais detalhes <i class="fa-solid fa-circle-arrow-right"></i>
            </a>
        </div>
    </div>

</div>