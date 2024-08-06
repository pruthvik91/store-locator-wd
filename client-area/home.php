<?php
require_once('../config.php');
require_once('uservalidation.php');
require_once('header.php'); ?>

<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
  <div>
    <h4 class="mb-3 mb-md-0">Welcome to Dashboard</h4>
  </div>
  <div class="d-flex align-items-center flex-wrap text-nowrap">
    <div class="input-group date datepicker dashboard-date mr-2 mb-2 mb-md-0 d-md-none d-xl-flex" id="dashboardDate">
      <span class="input-group-addon bg-transparent"><i data-feather="calendar" class=" text-primary"></i></span>
      <input type="text" class="form-control">
    </div>
    <button type="button" class="btn btn-outline-info btn-icon-text mr-2 d-none d-md-block">
      <i class="btn-icon-prepend" data-feather="download"></i>
      Import
    </button>
    <button type="button" class="btn btn-outline-primary btn-icon-text mr-2 mb-2 mb-md-0">
      <i class="btn-icon-prepend" data-feather="printer"></i>
      Print
    </button>
    <button type="button" class="btn btn-primary btn-icon-text mb-2 mb-md-0">
      <i class="btn-icon-prepend" data-feather="download-cloud"></i>
      Download Report
    </button>
  </div>
</div>

<div class="row">
  <div class="col-12 col-xl-12 stretch-card">
    <div class="row flex-grow">
      <div class="col-md-4 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-baseline">
              <h6 class="card-title mb-0">New Customers</h6>
              <div class="dropdown mb-2">
                <button class="btn p-0" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="icon-lg text-muted pb-3px" data-feather="more-horizontal"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                  <a class="dropdown-item d-flex align-items-center" href="#"><i data-feather="eye" class="icon-sm mr-2"></i> <span class="">View</span></a>
                  <a class="dropdown-item d-flex align-items-center" href="#"><i data-feather="edit-2" class="icon-sm mr-2"></i> <span class="">Edit</span></a>
                  <a class="dropdown-item d-flex align-items-center" href="#"><i data-feather="trash" class="icon-sm mr-2"></i> <span class="">Delete</span></a>
                  <a class="dropdown-item d-flex align-items-center" href="#"><i data-feather="printer" class="icon-sm mr-2"></i> <span class="">Print</span></a>
                  <a class="dropdown-item d-flex align-items-center" href="#"><i data-feather="download" class="icon-sm mr-2"></i> <span class="">Download</span></a>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-6 col-md-12 col-xl-5">
                <h3 class="mb-2">3,897</h3>
                <div class="d-flex align-items-baseline">
                  <p class="text-success">
                    <span>+3.3%</span>
                    <i data-feather="arrow-up" class="icon-sm mb-1"></i>
                  </p>
                </div>
              </div>
              <div class="col-6 col-md-12 col-xl-7">
                <div id="apexChart1" class="mt-md-3 mt-xl-0"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-4 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-baseline">
              <h6 class="card-title mb-0">New Orders</h6>
              <div class="dropdown mb-2">
                <button class="btn p-0" type="button" id="dropdownMenuButton1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="icon-lg text-muted pb-3px" data-feather="more-horizontal"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton1">
                  <a class="dropdown-item d-flex align-items-center" href="#"><i data-feather="eye" class="icon-sm mr-2"></i> <span class="">View</span></a>
                  <a class="dropdown-item d-flex align-items-center" href="#"><i data-feather="edit-2" class="icon-sm mr-2"></i> <span class="">Edit</span></a>
                  <a class="dropdown-item d-flex align-items-center" href="#"><i data-feather="trash" class="icon-sm mr-2"></i> <span class="">Delete</span></a>
                  <a class="dropdown-item d-flex align-items-center" href="#"><i data-feather="printer" class="icon-sm mr-2"></i> <span class="">Print</span></a>
                  <a class="dropdown-item d-flex align-items-center" href="#"><i data-feather="download" class="icon-sm mr-2"></i> <span class="">Download</span></a>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-6 col-md-12 col-xl-5">
                <h3 class="mb-2">35,084</h3>
                <div class="d-flex align-items-baseline">
                  <p class="text-danger">
                    <span>-2.8%</span>
                    <i data-feather="arrow-down" class="icon-sm mb-1"></i>
                  </p>
                </div>
              </div>
              <div class="col-6 col-md-12 col-xl-7">
                <div id="apexChart2" class="mt-md-3 mt-xl-0"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-4 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-baseline">
              <h6 class="card-title mb-0">Growth</h6>
              <div class="dropdown mb-2">
                <button class="btn p-0" type="button" id="dropdownMenuButton2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="icon-lg text-muted pb-3px" data-feather="more-horizontal"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton2">
                  <a class="dropdown-item d-flex align-items-center" href="#"><i data-feather="eye" class="icon-sm mr-2"></i> <span class="">View</span></a>
                  <a class="dropdown-item d-flex align-items-center" href="#"><i data-feather="edit-2" class="icon-sm mr-2"></i> <span class="">Edit</span></a>
                  <a class="dropdown-item d-flex align-items-center" href="#"><i data-feather="trash" class="icon-sm mr-2"></i> <span class="">Delete</span></a>
                  <a class="dropdown-item d-flex align-items-center" href="#"><i data-feather="printer" class="icon-sm mr-2"></i> <span class="">Print</span></a>
                  <a class="dropdown-item d-flex align-items-center" href="#"><i data-feather="download" class="icon-sm mr-2"></i> <span class="">Download</span></a>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-6 col-md-12 col-xl-5">
                <h3 class="mb-2">89.87%</h3>
                <div class="d-flex align-items-baseline">
                  <p class="text-success">
                    <span>+2.8%</span>
                    <i data-feather="arrow-up" class="icon-sm mb-1"></i>
                  </p>
                </div>
              </div>
              <div class="col-6 col-md-12 col-xl-7">
                <div id="apexChart3" class="mt-md-3 mt-xl-0"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div> <!-- row -->

<div class="quick-links">
  <div class="row align-item-center">
    <div class="col-12 col-xl-12 stretch-card">
    <div class="span3 span-md-6 span-sm-6 span-xs-12 mr-4">
      </div>
    <div class="span3 span-md-6 span-sm-6 span-xs-12 mr-4">
      <a href="saleinvoice.php" class="quick-link-card">
        <svg width="55" height="54" viewBox="0 0 55 54" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M29.625 36.0002H16.125C15.5283 36.0002 14.956 36.2372 14.534 36.6592C14.1121 37.0811 13.875 37.6534 13.875 38.2502C13.875 38.8469 14.1121 39.4192 14.534 39.8411C14.956 40.2631 15.5283 40.5002 16.125 40.5002H29.625C30.2218 40.5002 30.7941 40.2631 31.216 39.8411C31.638 39.4192 31.875 38.8469 31.875 38.2502C31.875 37.6534 31.638 37.0811 31.216 36.6592C30.7941 36.2372 30.2218 36.0002 29.625 36.0002ZM20.625 22.5002H25.125C25.7218 22.5002 26.2941 22.2631 26.716 21.8411C27.138 21.4192 27.375 20.8469 27.375 20.2502C27.375 19.6534 27.138 19.0811 26.716 18.6592C26.2941 18.2372 25.7218 18.0002 25.125 18.0002H20.625C20.0283 18.0002 19.456 18.2372 19.034 18.6592C18.6121 19.0811 18.375 19.6534 18.375 20.2502C18.375 20.8469 18.6121 21.4192 19.034 21.8411C19.456 22.2631 20.0283 22.5002 20.625 22.5002ZM47.625 27.0002H40.875V6.75015C40.8766 6.35368 40.7734 5.96383 40.5758 5.62008C40.3783 5.27633 40.0934 4.99089 39.75 4.79265C39.408 4.59518 39.02 4.49121 38.625 4.49121C38.2301 4.49121 37.8421 4.59518 37.5 4.79265L30.75 8.66265L24 4.79265C23.658 4.59518 23.27 4.49121 22.875 4.49121C22.4801 4.49121 22.0921 4.59518 21.75 4.79265L15 8.66265L8.25002 4.79265C7.90797 4.59518 7.51998 4.49121 7.12502 4.49121C6.73006 4.49121 6.34206 4.59518 6.00002 4.79265C5.65666 4.99089 5.37178 5.27633 5.17422 5.62008C4.97666 5.96383 4.87344 6.35368 4.87502 6.75015V42.7502C4.87502 44.5404 5.58618 46.2573 6.85205 47.5231C8.11792 48.789 9.83481 49.5002 11.625 49.5002H43.125C44.9152 49.5002 46.6321 48.789 47.898 47.5231C49.1639 46.2573 49.875 44.5404 49.875 42.7502V29.2502C49.875 28.6534 49.638 28.0811 49.216 27.6592C48.7941 27.2372 48.2218 27.0002 47.625 27.0002ZM11.625 45.0002C11.0283 45.0002 10.456 44.7631 10.034 44.3411C9.61207 43.9192 9.37502 43.3469 9.37502 42.7502V10.6427L13.875 13.2077C14.2223 13.389 14.6082 13.4838 15 13.4838C15.3918 13.4838 15.7778 13.389 16.125 13.2077L22.875 9.33765L29.625 13.2077C29.9723 13.389 30.3582 13.4838 30.75 13.4838C31.1418 13.4838 31.5278 13.389 31.875 13.2077L36.375 10.6427V42.7502C36.3811 43.5177 36.5181 44.2786 36.78 45.0002H11.625ZM45.375 42.7502C45.375 43.3469 45.138 43.9192 44.716 44.3411C44.2941 44.7631 43.7218 45.0002 43.125 45.0002C42.5283 45.0002 41.956 44.7631 41.534 44.3411C41.1121 43.9192 40.875 43.3469 40.875 42.7502V31.5002H45.375V42.7502ZM29.625 27.0002H16.125C15.5283 27.0002 14.956 27.2372 14.534 27.6592C14.1121 28.0811 13.875 28.6534 13.875 29.2502C13.875 29.8469 14.1121 30.4192 14.534 30.8411C14.956 31.2631 15.5283 31.5002 16.125 31.5002H29.625C30.2218 31.5002 30.7941 31.2631 31.216 30.8411C31.638 30.4192 31.875 29.8469 31.875 29.2502C31.875 28.6534 31.638 28.0811 31.216 27.6592C30.7941 27.2372 30.2218 27.0002 29.625 27.0002Z" fill="#727cf5" />
        </svg>
        <p>Sale Invoice</p>
      </a>
    </div>

    <div class="span3 span-md-6 span-sm-6 span-xs-12 ">
      <a href="add-products.php" class="quick-link-card">
        <svg width="54" height="54" viewBox="0 0 54 54" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M33.75 15.75H20.25M33.75 24.75H20.25M33.75 33.75H24.75M11.25 6.75H42.75V47.25L40.428 45.261C39.6124 44.562 38.5737 44.1778 37.4996 44.1778C36.4255 44.1778 35.3868 44.562 34.5712 45.261L32.2493 47.25L29.9295 45.261C29.1138 44.5614 28.0746 44.1768 27 44.1768C25.9254 44.1768 24.8862 44.5614 24.0705 45.261L21.7507 47.25L19.4288 45.261C18.6132 44.562 17.5745 44.1778 16.5004 44.1778C15.4263 44.1778 14.3876 44.562 13.572 45.261L11.25 47.25V6.75Z" stroke="#727cf5" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
        <p>Add Product</p>
      </a>
    </div>
    <div class="span3 span-md-6 span-sm-6 span-xs-12 mr-4">
      </div>
    </div>
  </div>

</div>
<?php require_once('footer.php'); ?>