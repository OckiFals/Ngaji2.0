# Ngaji Foundation 2.0.1

2.0
+ Pada versi ini, class diorganisasikan ulang menggunakan namespace
+ Ditambahkan class helpers Html untuk bekerja lebih nyaman dengan tag-tag HTML
+ Ditambahkan class Request untuk menampilkan informasi request dari client
+ Ditambahkan class Response untuk menampilkan informasi response dari web-server

2.0.1
+ Ditambahkan class QueryBuilder untuk menangani kueri SQL kompleks tanpa melalui Model Class
+ Ditambahkan function __autoloader() pada class Bootstrap2 untuk menangani eksepsi undefined class.
  Eksepsi tersebut dibangkitkan ketika meload class yang dibutuhkan, tetapi class tersebut tidak
  terdaftar pada app/settings.php

  NB: tidak disarankan untuk performa, sebisa mungkin daftarkan class pada settings.php

# Manual penggunaan:

###1. Definisikan web-app path
   contoh: asumsikan url path untuk project adalah http://ockifals.dev/bisangaji
   maka ubah definisi app root pada index.php menjadi:
   
   
   ```php 
   define('HOSTNAME', '/bisangaji');
   ```
   
   
   Untuk versi ini, telah digunakan fungsi untuk mendapatkan nama master folder untuk project yang bersangkutan.
   Oleh karenanya mendefinisikan web-app path tidak lagi menjadi suatu keharusan(optional).
   
   
###2. Ubah dan sesuaikan konfigurasi pada App/setting.php
   File tersebut merupakan konfigurasi fundamental yang dimuat ketika aplikasi web dijalankan.
   
   **2.1 Konfigurasi database**
   
      
      ```php
      
      'db' => [
	    'name' => 'nama_database',
    	    'host' => 'host db server, default: localhost',
    	    'user' => 'username akun',
    	    'pass' => 'password akun'
      ],
      
      ```
  
  
   **2.2 Daftarkan contoller atau user defined clas(optional)**
   
   
       Perlu diketahui bahwa class di daftarkan dengan full path
       
       
       ```php
       'class' => [
           'Ngaji/Routing/Route.php',
           'app/Controllers/UstadzController.php'
           ...
        ```
        
        
   **2.3 Daftarkan model(optional)**
   
   
       Model didaftarkan tanpa full path, tambahkan hanya nama filenya saja.
       
       Misal terdapat model Ustadz di /app/models/Ustadz.php. 
       Untuk mendaftarkannya, tidak perlu menuliskan '/app/models/Ustadz.php' cukup hanya 'Ustadz'
       
       
       ```
       'models' => [
           'Ustadz',
           ...
        ```
       
       
       Ketika model digunakan pada contoller, jangan lupa untuk memanggil model tersebut pada App namespace.
       
       contoh:
       pada baris controller paling atas tambahkan
       
       
       ```php
       use app\models\Ustadz;
       
       Sehingga:
       
       <?php namespace app\contoller;

       use app\models\Ustadz;
       
       class ControllerName extends Controller {
       
       ....
       
       }
	```
	
	
###3. Sesuaikan route
   Ngaji/Routing/Route.php merupakan class yang bertugas mengarahkan request dari client. 
   Request tersebut akan ditentukan jalurnya dengan memanggil controller yang sesuai.
   
   $this->router->map('method', 'uri', function () {
            panggil controller disini
        }, '[alias: optional]');
        
   Contoh:

   $this->router->map('GET|POST', '/index.php/login', function () {
            Controller::login();
        }, 'login');
   
   Penjelasan:
   Route '/index.php/login' diatas hanya memperbolehkan method GET dan POST. 
   Ketika route tersebut dipanggil akan dialihkan controller Controller dengan action login.
   Route tersebut memiliki alias 'login'.
   
   NB:
   1. Nama alias route harus unik
   2. Semua route harus melalui index.php, dengan pengecualian
      Benar: /index.php/login tidak benar: /login
      Route tidak bisa bekerja langsung pada URI /login kecuali mengubah konfigurasi pada .htaccess
      
      Untuk mendefinisikan sendiri route /login tanpa melalui index.php
      Tambahkan baris kode dibawah pada file .htaccess(hanya untuk web-service Apache2)
      
      RewriteRule ^login/?$ index.php/login [QSA,L]
   
###4. Bekerja dengan Html helpers
   **4.1 Html::Load()**
       
       Helper ini digunakan pada view untuk memuat file JS, CSS, dan image secara dinamis
       
       Bentuk umum:
       <?= Html::load('[jenis-file]', '[path-file]') ?>
       
       NB: jika file yang dipanggil terdapat pada direkroti default, maka tidak perlu menuliskan path secara lengkap
       
       Adapun direktori default yang diakui:
       CSS: /assets/css
       JS: /assets/js
       IMG: /assets/img
       
       **4.1.1 Load CSS dan JS**
      	  Contoh 1, terdapat file style.css pada direktori default /assets/css
      	  
      	  <?= Html::load('css', 'style.css') ?>
      	  
      	  Contoh 2, terdapat file angular.js pada direktori /assets/dist/js.
      	  Untuk load gunakan full path(setelah assets) untuk file js tersebut
      	  
      	  <?= Html::load('js', 'dist/js/angular.js') ?>
       
       **4.1.2 Load image**
       
       
          Contoh 1: tanpa atribut
          
          
          `<?= Html::load('img', 'avatar.png') ?>`
          
          Kode diatas akan menghasilkan:
          
          
	        `<img src="/[hostname-app]/assets/img/avatar.png"/>`
	  
	  
          Contoh 2: dengan atribut
      	  ```php
      	      <?= Html::load('img', 'avatar.png', [
        		    'class' => 'user-image',
        		    'alt' => 'User Image'
      	      ])
      	  ?>
      	  ```
      	  
      	  Kode diatas akan menghasilkan:
      	  
      	  
      	  `<img src="/[hostname-app]/assets/img/avatar.png" class="user-image" alt="User Image"/>`

  **4.2 `Html::anchor()`**
  
  
	```php
      Helper ini digunakan pada view untuk membuat link anchor( a href )
      
      Bentuk umum:
      <?= Html::anchor('/[path]', 'teks', [atribut:optional]) ?>
      
      Contoh:
      <?= Html::anchor('/login', 'Login Disini', [
              'class' => [
                    'btn',
                    'btn-default',
                    'btn-flat'
              ]
          ])
      ?>

      Kode diatas akan menghasilkan:
      <a href="/[hostname-app]/login" class="btn btn-default btn-flat">Login Disini</a>
	```
### 5. Bekerja dengan database
   **5.1 `Model::all()`**
   
   
	Mengambil seluruh baris data dari suatu model
	
	
	Contoh:
	```php
	use app\models\Ustadz;
	class Example extend Controller{
		public static function test{ 
		$data = Ustadz::all();
	```
	
	
   **5.2 `Model::findOne()`**
	Mengambil satu baris data dengan criteria atau tanpa criteria
	
	**5.2.1 Mengambil satu data teratas**
	```php
	$data = Ustadz::findOne();
	```
	
	**5.2.2 Mencari berdasarkan primary key**
	```php
	$data = Ustadz::findOne(2);
	```
	
	**5.2.2 Mencari berdasarkan kriteria nilai tertentu**
	```php
	$data = Ustadz::findOne([
		'username' => 'subali'
	]);
	```
	
    **5.3 `Model::findAll()`**
    
    
	Mengambil seluruh baris data dengan criteria atau tanpa criteria
	
	**5.2.1 Mencari berdasarkan kriteria nilai tertentu**
	```php
	$data = Ustadz::findAll([
		'type` => 1,
		'active' => 1
	]);
	```
	
	
	Setara dengan:
	
	
	```sql
	SELECT ... FROM ... WHERE `type`=1 AND `active`=1
	```
	
	
	```php
	$data = Ustadz::findAll([
		'type` => 1,
		'active' => [
			'!=` => 1
		]
	]);
	```
	
	
	Setara dengan:
	
	
	```sql
	SELECT ... FROM ... WHERE `type`=1 AND `active`!=1
	```
	
	**5.2.2 Mencari berdasarkan kriteria nilai tertentu**
	```php
	$data = Ustadz::findOne([
		'username' => 'subali'
	]);
	```
	
