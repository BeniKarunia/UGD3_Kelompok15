package com.example.ugd3_kelompok15.ui.home

import android.content.Intent
import android.os.Bundle
import androidx.fragment.app.Fragment
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.Button
import android.widget.TextView
import com.example.ugd3_kelompok15.LoginActivity
import com.example.ugd3_kelompok15.R
import com.example.ugd3_kelompok15.ui.faq.FragmentFaq
import com.example.ugd3_kelompok15.ui.informasidokter.FragmentDokter
import com.example.ugd3_kelompok15.ui.janjitemu.FragmentJanjiTemu
import com.example.ugd3_kelompok15.ui.profile.FragmentProfile
import com.google.android.material.bottomnavigation.BottomNavigationView
import kotlin.system.exitProcess

class FragmentHome : Fragment() {

    override fun onCreateView(
        inflater: LayoutInflater, container: ViewGroup?,
        savedInstanceState: Bundle?
    ): View? {
        // Inflate the layout for this fragment
        return inflater.inflate(R.layout.fragment_home, container, false)
    }
    override fun onViewCreated(view: View, savedInstanceState: Bundle?) {
        super.onViewCreated(view, savedInstanceState)

        val btnLogout : Button = view.findViewById(R.id.btnLogout)

        btnLogout.setOnClickListener(View.OnClickListener {
            exitProcess(0)
        })
    }

    private fun transitionFragment(fragment: Fragment) {
        val transition = requireActivity().supportFragmentManager.beginTransaction()
        transition.replace(R.id.layout_home, fragment)
            .addToBackStack(null).commit()
    }
}