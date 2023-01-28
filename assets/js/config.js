import React from "react";

export const pathRecipe = "/recipes/" ;
export const pathCategory = "/category/" ;
export const pathIngredient = "/ingredients/" ;
export const AuthContext = React.createContext(0);
export const ingredientsUnit = [
    {
        catName : 'Solide',
        catValues:[
            {
                name:'Milligramme (mg)',
                value:'mg',
            },
            {
                name:'Gramme (g)',
                value:'g',
            },
            {
                name:'Kilogramme (Kg)',
                value:'Kg',
            },
            {
                name:'Centimetre (cm)',
                value:'cm',
            },
            {
                name:'C. à soupe',
                value:'cs',
            },
            {
                name:'C. à café',
                value:'cc',
            }],

    },{
        catName : 'Liquide',
        catValues:[
            {
                name:'Millilitre (ml)',
                value:'ml',
            },
            {
                name:'Centilitre (cl)',
                value:'cl',
            },
            {
                name:'Litre (L)',
                value:'L',
            },
           ],

    },{
        catName : 'Autre',
        catValues:[
            {
                name:'bouquet',
                value:'bouquet',
            },
            {
                name:'gousse',
                value:'gousse',
            },
            {
                name:'graine',
                value:'graine',
            },
            {
                name:'pincée',
                value:'pince',
            },
            {
                name:'unité',
                value:'unit',
            },
        ],

    }]

{/* <ListSubheader className="text-black text-bg-light">Solide</ListSubheader>
                        <MenuItem className="text-secondary" value="mg">Milligramme (mg)</MenuItem>
                        <MenuItem className="text-secondary" value="g">Gramme (g)</MenuItem>
                        <MenuItem className="text-secondary" value="Kg">Kilogramme (Kg)</MenuItem>
                        <MenuItem className="text-secondary" value="cm">Centimetre (cm)</MenuItem>
                        <MenuItem className="text-secondary" value="cs">C. à soupe</MenuItem>
                        <MenuItem className="text-secondary" value="cc">C. à café</MenuItem>
                    <ListSubheader className="text-black text-bg-light">Liquide</ListSubheader>
                        <MenuItem className="text-secondary" value="ml">Millilitre (ml)</MenuItem>
                        <MenuItem className="text-secondary" value="cl">Centilitre (cl)</MenuItem>
                        <MenuItem className="text-secondary" value="L">Litre (L)</MenuItem>
                    <ListSubheader className="text-black text-bg-light">Autre</ListSubheader>
                        <MenuItem className="text-secondary" value="bouquet">bouquet</MenuItem>
                        <MenuItem className="text-secondary" value="gousse">gousse</MenuItem>
                        <MenuItem className="text-secondary" value="graine">graine</MenuItem>
                        <MenuItem className="text-secondary" value="pince">pincée</MenuItem>
                        <MenuItem className="text-secondary" value="unit">unité</MenuItem>*/}