package com.example.ugd3_kelompok15

import android.content.Context
import android.content.SharedPreferences
import androidx.appcompat.app.AppCompatActivity
import android.os.Bundle
import android.util.Log
import android.widget.TextView
import androidx.appcompat.app.AlertDialog
import androidx.fragment.app.Fragment
import com.example.ugd3_kelompok15.ui.faq.FragmentFaq
import com.example.ugd3_kelompok15.ui.home.FragmentHome
import com.example.ugd3_kelompok15.ui.informasidokter.FragmentDokter
import com.example.ugd3_kelompok15.ui.lokasi.FragmentLokasiRS
import com.example.ugd3_kelompok15.ui.profile.FragmentProfile
import com.google.android.material.bottomnavigation.BottomNavigationView
import nl.joery.animatedbottombar.AnimatedBottomBar
import kotlin.system.exitProcess

class HomeActivity : AppCompatActivity() {
    private lateinit var navigationBottom : AnimatedBottomBar
    private lateinit var textView : TextView
    private lateinit var sharedPreferences: SharedPreferences

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_home)

        sharedPreferences = getSharedPreferences("login", Context.MODE_PRIVATE)
        setTitle("My Hospital")

        changeFragment(FragmentHome())

        init()
        navListener()

    }
    private fun init(){
        textView = findViewById(R.id.textWelcome)
        navigationBottom = findViewById(R.id.botNavigation)
    }
    fun changeFragment(fragment: Fragment?) {
        if (fragment != null) {
            getSupportFragmentManager()
                .beginTransaction()
                .replace(R.id.layout_fragment, fragment)
                .commit()
        }
    }
//    private fun navListener() {
//        navigationBottom.setOnItemSelectedListener { item ->
//            when (item.itemId) {
//                R.id.Home -> {
//                   textView.text = item.title
//                    changeFragment(FragmentHome())
//                    return@setOnItemSelectedListener true
//                }
//                R.id.informasi_dokter -> {
//                    textView.text = null
//                    changeFragment(FragmentDokter())
//                    return@setOnItemSelectedListener true
//                }
//                R.id.lokasi_rs -> {
//                    textView.text = null
//                    changeFragment(FragmentLokasiRS())
//                    return@setOnItemSelectedListener true
//                }
//                R.id.faq -> {
//                    textView.text = null
//                    changeFragment(FragmentFaq())
//                    return@setOnItemSelectedListener true
//                }
//                R.id.profile -> {
//                    textView.text = null
//                    changeFragment(FragmentProfile())
//                    return@setOnItemSelectedListener true
//                }
//            }
//            false
//        }
//    }

    private fun navListener() {
        navigationBottom.setOnTabSelectListener(object : AnimatedBottomBar.OnTabSelectListener {
            override fun onTabSelected(
                lastIndex: Int,
                lastTab: AnimatedBottomBar.Tab?,
                newIndex: Int,
                newTab: AnimatedBottomBar.Tab
            ) {
                when(newIndex) {
                    0 -> changeFragment(FragmentHome())
                    1 -> changeFragment(FragmentDokter())
                    2 -> changeFragment(FragmentLokasiRS())
                    3 -> changeFragment(FragmentFaq())
                    4 -> changeFragment(FragmentProfile())
                    else -> changeFragment(FragmentHome())
                }
                Log.d("bottom_bar", "Selected index: $newIndex, title: ${newTab.title}")
            }

            override fun onTabReselected(index: Int, tab: AnimatedBottomBar.Tab) {
                Log.d("bottom_bar", "Reselected index: $index, title: ${tab.title}")
            }
        })
    }

    override fun onBackPressed() {
        AlertDialog.Builder(this).apply {
            setTitle("Tolong Konfirmasi")
            setMessage("Apakah anda yakin ingin keluar?")

            setPositiveButton("Iya") { _, _ ->
                moveTaskToBack(true)
                android.os.Process.killProcess(android.os.Process.myPid())
                System.exit(1)

            }

            setNegativeButton("Tidak"){_, _ ->
            }

            setCancelable(true)
        }.create().show()
    }

    fun getSharedPreferences() : SharedPreferences {
        return sharedPreferences
    }



}