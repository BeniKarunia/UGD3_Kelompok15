package com.example.ugd3_kelompok15

import android.app.AlertDialog
import android.app.NotificationChannel
import android.app.NotificationManager
import android.app.PendingIntent
import android.content.Context
import android.content.Intent
import android.graphics.BitmapFactory
import android.graphics.Color
import android.os.Build
import androidx.appcompat.app.AppCompatActivity
import android.os.Bundle
import android.util.Log
import android.util.Patterns
import android.view.View
import android.widget.Toast
import androidx.core.app.NotificationCompat
import androidx.core.app.NotificationManagerCompat
import androidx.core.content.res.ResourcesCompat
import com.android.volley.AuthFailureError
import com.android.volley.RequestQueue
import com.android.volley.Response
import com.android.volley.toolbox.StringRequest
import com.android.volley.toolbox.Volley
import com.example.ugd3_kelompok15.api.UserProfilApi
import com.example.ugd3_kelompok15.databinding.ActivityRegisterBinding
import com.example.ugd3_kelompok15.models.UserProfil
import com.example.ugd3_kelompok15.room.UserDB
import com.google.firebase.auth.FirebaseAuth
import com.google.gson.Gson
import com.parse.ParseObject
import org.json.JSONObject
import www.sanju.motiontoast.MotionToast
import www.sanju.motiontoast.MotionToastStyle
import java.lang.Exception
import java.nio.charset.StandardCharsets
import kotlin.jvm.Throws
import com.parse.ParseException;
import com.parse.ParseUser
import com.parse.SignUpCallback;

class RegisterActivity : AppCompatActivity() {
    private lateinit var binding: ActivityRegisterBinding
    private val CHANNEL_ID_1 = "channel_01"
    private val notificationId1 = 101
    private var queue: RequestQueue? = null
    private var checkRegister = true
    private lateinit var auth: FirebaseAuth

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        binding = ActivityRegisterBinding.inflate(layoutInflater)
        val view = binding.root
        setContentView(view)

        val db by lazy { UserDB(this) }
        val userDao = db.userDao()


        auth = FirebaseAuth.getInstance()

        supportActionBar?.hide()

        createChannel()

        var inputNama = binding.inputLayoutNama
        var inputUsername = binding.inputLayoutUsername
        var inputEmail = binding.inputLayoutEmail
        var inputNoTelp = binding.inputLayoutNoTelp
        var inputPassword = binding.inputLayoutPassword

        queue = Volley.newRequestQueue(this)

        binding.btnRegister.setOnClickListener(View.OnClickListener{
            val intent = Intent(this, LoginActivity::class.java)
            val mBundle = Bundle()

            val nama: String = binding.inputLayoutNama.getEditText()?.getText().toString()
            val username: String = binding.inputLayoutUsername.getEditText()?.getText().toString()
            val email: String = binding.inputLayoutEmail.getEditText()?.getText().toString()
            val noTelp: String = binding.inputLayoutNoTelp.getEditText()?.getText().toString()
            val password: String = binding.inputLayoutPassword.getEditText()?.getText().toString()

//            var checkRegister = true

            mBundle.putString("tietNama" , nama)
            mBundle.putString("tietUsername" , username)
            mBundle.putString("tietEmail" , email)
            mBundle.putString("tietNomor" , noTelp)
            mBundle.putString("tietPassword" , password)

            if(nama.isEmpty()){
                inputNama.setError("Nama must be filled with text")
                checkRegister = false
            }else if(username.isEmpty()){
                inputUsername.setError("Username must be filled with text")
                checkRegister = false
            }else if(!email.isValidEmail()) {
                inputEmail.setError("Email must be filled with valid format")
                checkRegister = false
            }else if(noTelp.isEmpty() || noTelp.length < 10 || noTelp.length > 12){
                inputNoTelp.setError("No Telp must be filled with min 10 digit and max 12 digit")
                checkRegister = false
            }else if(password.isEmpty()){
                inputPassword.setError("Password must be filled with text")
                checkRegister = false
            }

            if(!nama.isEmpty()){
                inputNama.setError(null)
//                checkRegister = true
            }
            if(!username.isEmpty()){
                inputUsername.setError(null)
            }
            if(email.isValidEmail()) {
                inputEmail.setError(null)
            }
            if(!noTelp.isEmpty() && noTelp.length >= 10 && noTelp.length < 12){
                inputNoTelp.setError(null)
            }
            if(!password.isEmpty()){
                inputPassword.setError(null)
            }

            if(!checkRegister){
                checkRegister = true
                return@OnClickListener
            }else {
                //Create User Profil with Volley
//                registerFirebase(email, password)
                signUpwithBack4app(username,password,email)
                createUser(mBundle)
            }

        })
    }

    private fun signUpwithBack4app(username: String, password: String, email: String) {
        val user = ParseUser()
        user.username = username
        user.setPassword(password)
        user.email = email
        user.signUpInBackground(SignUpCallback {
            if (it == null) {
                ParseUser.logOut();
                MotionToast.darkColorToast(this@RegisterActivity,"Notification Register!",
                    "Email verifikasi sudah dikirim",
                    MotionToastStyle.SUCCESS,
                    MotionToast.GRAVITY_BOTTOM,
                    MotionToast.LONG_DURATION,
                    ResourcesCompat.getFont(this@RegisterActivity, www.sanju.motiontoast.R.font.helvetica_regular))
            } else {
                ParseUser.logOut();
                MotionToast.darkColorToast(this@RegisterActivity,"Notification Register!",
                    "Account could not be created" + " :" + it.message,
                    MotionToastStyle.SUCCESS,
                    MotionToast.GRAVITY_BOTTOM,
                    MotionToast.LONG_DURATION,
                    ResourcesCompat.getFont(this@RegisterActivity, www.sanju.motiontoast.R.font.helvetica_regular))
            }
        })
    }

    private fun showAlert(title: String, message: String, error: Boolean) {
        val builder = AlertDialog.Builder(this)
            .setTitle(title)
            .setMessage(message)
            .setPositiveButton("OK") { dialog, which ->
                dialog.cancel()
                // don't forget to change the line below with the names of your Activities
//                if (!error) {
//                    val intent = Intent(this@RegisterActivity, LoginActivity::class.java)
//                    intent.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TASK or Intent.FLAG_ACTIVITY_NEW_TASK)
//                    startActivity(intent)
//                }
            }
        val ok = builder.create()
        ok.show()
    }

    private fun createChannel() {
        if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.O) {
            val name = "Notification Title"
            val descriptionText = "Notification Description"

            val channel1 = NotificationChannel(CHANNEL_ID_1, name, NotificationManager.IMPORTANCE_DEFAULT).apply {
                description = descriptionText
            }

            val notificationManager: NotificationManager = getSystemService(Context.NOTIFICATION_SERVICE)  as NotificationManager

            notificationManager.createNotificationChannel(channel1)
        }
    }

    private fun sendNotification() {

        val broadcastIntent: Intent = Intent(this, NotificationReceiver:: class.java)
        broadcastIntent.putExtra("toastMessage", "Hi " + binding.inputLayoutNama.getEditText()?.getText().toString() + " :)")
        val actionIntent = PendingIntent.getBroadcast(this, 0, broadcastIntent, PendingIntent.FLAG_MUTABLE)

        val image = BitmapFactory.decodeResource(resources, R.drawable.gambar_rs)

        val builder = NotificationCompat.Builder(this, CHANNEL_ID_1)
            .setSmallIcon(R.drawable.ic_baseline_notifications_24)
            .setContentTitle("Notification Register")
            .setContentText("Selamat Datang di My Hospital")
            .setLargeIcon(image)
            .setStyle(NotificationCompat.BigPictureStyle()
                .bigLargeIcon(null)
                .bigPicture(image))
            .setCategory(NotificationCompat.CATEGORY_MESSAGE)
            .setColor(Color.BLUE)
            .setAutoCancel(true)
            .setOnlyAlertOnce(true)
            .addAction(R.mipmap.ic_launcher, "From Us", actionIntent)
            .setPriority(NotificationCompat.PRIORITY_DEFAULT)

        with(NotificationManagerCompat.from(this)) {
            notify(notificationId1, builder.build())
        }
    }
    private fun createUser(mBundle: Bundle) {
        val userprofil = UserProfil(
            0,
            binding.inputLayoutNama.getEditText()?.getText().toString(),
            binding.inputLayoutUsername.getEditText()?.getText().toString(),
            binding.inputLayoutEmail.getEditText()?.getText().toString(),
            binding.inputLayoutNoTelp.getEditText()?.getText().toString(),
            binding.inputLayoutPassword.getEditText()?.getText().toString()
        )
        val stringRequest: StringRequest =
            object : StringRequest(Method.POST, UserProfilApi.REGISTER, Response.Listener { response ->
                val gson = Gson()
                var user = gson.fromJson(response, UserProfil::class.java)

                if(user != null) {
                    MotionToast.darkColorToast(this,"Notification Register!",
                        "Register Berhasil!!",
                        MotionToastStyle.SUCCESS,
                        MotionToast.GRAVITY_BOTTOM,
                        MotionToast.LONG_DURATION,
                        ResourcesCompat.getFont(this, www.sanju.motiontoast.R.font.helvetica_regular))
                    val intent = Intent(this@RegisterActivity, LoginActivity::class.java)
                    sendNotification()
                    intent.putExtra("Register", mBundle)
                    startActivity(intent)
                } else {
                    checkRegister = false
                }
            }, Response.ErrorListener { error ->
                try {
                    val responseBody = String(error.networkResponse.data, StandardCharsets.UTF_8)
                    val errors = JSONObject(responseBody)
                    MotionToast.darkColorToast(this,"Notification Register!",
                        errors.getString("message"),
                        MotionToastStyle.INFO,
                        MotionToast.GRAVITY_BOTTOM,
                        MotionToast.LONG_DURATION,
                        ResourcesCompat.getFont(this, www.sanju.motiontoast.R.font.helvetica_regular))
                    checkRegister = false
                }catch (e: Exception) {
                    MotionToast.darkColorToast(this,"Notification Register!",
                        e.message.toString(),
                        MotionToastStyle.INFO,
                        MotionToast.GRAVITY_BOTTOM,
                        MotionToast.LONG_DURATION,
                        ResourcesCompat.getFont(this, www.sanju.motiontoast.R.font.helvetica_regular))
                }
            }) {
                @Throws(AuthFailureError::class)
                override fun getHeaders(): Map<String, String> {
                    val headers = HashMap<String, String>()
                    headers["Accept"] = "application/json"
                    return headers
                }

                @Throws(AuthFailureError::class)
                override fun getBody(): ByteArray {
                    val gson = Gson()
                    val requestBody = gson.toJson(userprofil)
                    return requestBody.toByteArray(StandardCharsets.UTF_8)
                }

                override fun getBodyContentType(): String {
                    return "application/json"
                }
            }
        queue!!.add(stringRequest)
    }
    fun CharSequence?.isValidEmail() = !
    isNullOrEmpty() && Patterns.EMAIL_ADDRESS.matcher(this).matches()
}