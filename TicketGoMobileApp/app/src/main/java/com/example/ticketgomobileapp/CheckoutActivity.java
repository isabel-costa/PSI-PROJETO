package com.example.ticketgomobileapp;

import android.content.Intent;
import android.os.Bundle;
import android.os.Handler;
import android.util.Log;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.RadioButton;
import android.widget.RadioGroup;
import android.widget.Toast;

import androidx.appcompat.app.AppCompatActivity;

public class CheckoutActivity extends AppCompatActivity {

    RadioGroup radioGroupPaymentOptions;
    RadioButton rbMultibanco, rbMbWay, rbCartaoCredito;

    EditText edtNumCartao, edtNomeTitular,edtValidade;

    Button btnConfirmarCompra;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_checkout);

        // Initialize RadioGroup and RadioButtons
        radioGroupPaymentOptions = findViewById(R.id.radioGroupPaymentOptions);
        edtNumCartao=findViewById(R.id.et_card_number);
        edtNomeTitular=findViewById(R.id.et_cardholder_name);
        edtValidade=findViewById(R.id.et_expiry_date);
        btnConfirmarCompra=findViewById(R.id.btn_confirm_purchase);

        // Set a listener for the RadioGroup to handle the selection of a payment option
        radioGroupPaymentOptions.setOnCheckedChangeListener(new RadioGroup.OnCheckedChangeListener() {
            @Override
            public void onCheckedChanged(RadioGroup group, int checkedId) {
                Log.d("CheckoutActivity", "Checked ID: " + checkedId);
                // Check which RadioButton is selected
                if (checkedId == R.id.rb_multibanco) {
                    Toast.makeText(CheckoutActivity.this, "Selected: Multibanco", Toast.LENGTH_SHORT).show();
                    edtNumCartao.setVisibility(View.GONE);
                    edtNomeTitular.setVisibility(View.GONE);
                    edtValidade.setVisibility(View.GONE);
                } else if (checkedId == R.id.rb_mbway) {
                    Toast.makeText(CheckoutActivity.this, "Selected: MBWay", Toast.LENGTH_SHORT).show();
                    edtNumCartao.setVisibility(View.GONE);
                    edtNomeTitular.setVisibility(View.GONE);
                    edtValidade.setVisibility(View.GONE);
                } else if (checkedId == R.id.rb_credit_card) {
                    Toast.makeText(CheckoutActivity.this, "Selected: Credit Card", Toast.LENGTH_SHORT).show();
                    edtNumCartao.setVisibility(View.VISIBLE);
                    edtNomeTitular.setVisibility(View.VISIBLE);
                    edtValidade.setVisibility(View.VISIBLE);
                }
            }
        });

        btnConfirmarCompra.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                Toast.makeText(CheckoutActivity.this,"Compra realizada com sucesso!",Toast.LENGTH_SHORT).show();
                // Cria um Handler para postergar a execução do código
                Handler handler = new Handler();
                handler.postDelayed(new Runnable() {
                    @Override
                    public void run() {
                        // Código que será executado após o delay
                        Intent intent= new Intent(CheckoutActivity.this,MainActivity.class);
                        startActivity(intent);
                    }
                }, 2000); // O tempo de delay é em milissegundos (2000ms = 2 segundos)

            }
        });
    }
}
