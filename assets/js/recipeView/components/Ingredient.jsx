import React, {useEffect, useState} from 'react';
import { pathIngredient } from '../../config';
const Base = ({recipeIngredient}) => {


    return (
        <div className="d-flex flex-column align-items-center m-2">
            <div style={{width: "80px",height: "80px"}}
                 className="card d-flex flex-column align-items-center justify-content-center shadow-sm mx-3 ">
                <img alt={recipeIngredient.ingredient.name} style={{ objectFit: "cover",width: "60px",maxHeight:"75px"}}
                     src={ pathIngredient + recipeIngredient.ingredient.picture }/>
            </div>
            <span>
                {recipeIngredient.quantity}
                {recipeIngredient.unit !== "unit" ? recipeIngredient.unit : "" }
            </span>
            <span style={{fontSize:"0.9em"}}> {recipeIngredient.ingredient.name}</span>
        </div>
    );
}
export default Base;